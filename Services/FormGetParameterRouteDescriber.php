<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Services;

use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Schema;
use ReflectionMethod;
use Sandronimus\NelmioFormGetParameterPluginBundle\Attribute\FormGetParameter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\Route;

final class FormGetParameterRouteDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    private FormFactoryInterface $formFactory;

    /**
     * FilterFormRouteDescriber constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param OpenApi          $api
     * @param Route            $route
     * @param ReflectionMethod $reflectionMethod
     */
    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod): void
    {
        foreach ($reflectionMethod->getAttributes(FormGetParameter::class) as $attribute) {
            $formGetParameter = $attribute->newInstance();

            $this->processFilterFormAnnotation($formGetParameter, $api, $route);
        }
    }

    /**
     * @param FormGetParameter $annotation
     * @param OpenApi          $api
     * @param Route            $route
     */
    private function processFilterFormAnnotation(FormGetParameter $annotation, OpenApi $api, Route $route): void
    {
        $filterForm = $this->formFactory->create($annotation->formType);

        $operations = $this->getOperations($api, $route);

        foreach ($operations as $operation) {
            foreach ($filterForm->all() as $formItem) {
                $name = $formItem->getName();
                if ($formItem->getParent() && $formItem->getParent()->getName()) {
                    $name = $formItem->getParent()->getName() . "[" . $formItem->getName() . "]";
                }

                if ($formItem->count() === 0) {
                    $parameter = Util::getOperationParameter($operation, $name, 'query');
                    $parameter->required = $formItem->getConfig()->getRequired();
                    /** @var Schema $schema */
                    $schema = Util::getChild($parameter, Schema::class);
                    $schema->type = $this->getParameterTypeForFormType(
                        $formItem->getConfig()->getType()->getInnerType()
                    );
                }

                if ($formItem->count() > 0) {
                    foreach ($formItem as $subForm) {
                        $subFormName = $name . "[" . $subForm->getName() . "]";

                        $parameter = Util::getOperationParameter($operation, $subFormName, 'query');
                        $parameter->required = $subForm->getConfig()->getRequired();
                        /** @var Schema $schema */
                        $schema = Util::getChild($parameter, Schema::class);
                        $schema->type = $this->getParameterTypeForFormType(
                            $subForm->getConfig()->getType()->getInnerType()
                        );
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
        if ($formType instanceof NumberType) {
            return 'number';
        }

        if ($formType instanceof IntegerType) {
            return 'integer';
        }

        if ($formType instanceof CheckboxType) {
            return 'boolean';
        }

        return 'string';
    }
}
