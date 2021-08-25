<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Services;

use Doctrine\Common\Annotations\Reader;
use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Swagger;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use ReflectionMethod;
use RuntimeException;
use Sandronimus\NelmioFormGetParameterPluginBundle\Annotation\FormGetParameter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\Route;

final class FormGetParameterRouteDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    /** @var Reader */
    private $annotationReader;

    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * FilterFormRouteDescriber constructor.
     *
     * @param Reader               $annotationReader
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(Reader $annotationReader, FormFactoryInterface $formFactory)
    {
        $this->annotationReader = $annotationReader;
        $this->formFactory      = $formFactory;
    }

    /**
     * @param Swagger           $api
     * @param Route             $route
     * @param ReflectionMethod $reflectionMethod
     */
    public function describe(Swagger $api, Route $route, ReflectionMethod $reflectionMethod): void
    {
        $annotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if (!$annotation instanceof FormGetParameter) {
                continue;
            }

            $this->processFilterFormAnnotation($annotation, $api, $route);
        }
    }

    /**
     * @param FormGetParameter $annotation
     * @param Swagger          $api
     * @param Route            $route
     */
    private function processFilterFormAnnotation(FormGetParameter $annotation, Swagger $api, Route $route): void
    {
        $filterForm = $this->formFactory->create($annotation->formType);

        $operations = $this->getOperations($api, $route);

        foreach ($operations as $operation) {
            $parameters = $operation->getParameters();

            foreach ($filterForm->all() as $formItem) {

                $name = $formItem->getName();
                if ($formItem->getParent() && $formItem->getParent()->getName()) {
                    $name = $formItem->getParent()->getName()."[".$formItem->getName()."]";
                }

                if ($formItem->count() == 0) {
                    $parameter = new Parameter([
                        'in'       => 'query',
                        'name'     => $name,
                        'required' => $formItem->getConfig()->getRequired(),
                        //'description' => '',
                        'type'     => $this->getParameterTypeForFormType(
                            $formItem->getConfig()->getType()->getInnerType()
                        ),
                    ]);
    
                    $parameters->add($parameter);
                }

                if ($formItem->count() > 0) {
                    foreach ($formItem as $subForm) {
                        $subFormName = $name."[".$subForm->getName()."]";

                        $parameter = new Parameter([
                            'in'       => 'query',
                            'name'     => $subFormName,
                            //'description' => '',
                            'required' => $subForm->getConfig()->getRequired(),
                            'type'     => $this->getParameterTypeForFormType(
                                $subForm->getConfig()->getType()->getInnerType()
                            ),
                        ]);
                        $parameters->add($parameter);
                    }
                }

            }
        }
    }

    /**
     * @param FormTypeInterface $formType
     *
     * @return string
     */
    private function getParameterTypeForFormType(FormTypeInterface $formType): string
    {
        if ($formType instanceof ChoiceType) {
            return 'choice';
        }

        if ($formType instanceof NumberType) {
            return 'number';
        }

        if ($formType instanceof IntegerType) {
            return 'integer';
        }

        if ($formType instanceof CheckboxType) {
            return 'boolean';
        }

        if ($formType instanceof DateType) {
            return 'date';
        }

        if ($formType instanceof DateTimeType) {
            return 'dateTime';
        }

        if ($formType instanceof CollectionType) {
            return 'collection';
        }

        return 'string';
    }
}
