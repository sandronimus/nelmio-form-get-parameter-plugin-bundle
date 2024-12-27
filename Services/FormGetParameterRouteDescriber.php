<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Services;

use Exception;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Schema;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionMethod;
use ReflectionProperty;
use Sandronimus\NelmioFormGetParameterPluginBundle\Attribute\FormGetParameter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Route;

final class FormGetParameterRouteDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;

    private FormFactoryInterface $formFactory;

    private DocBlockFactoryInterface $docBlockFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod): void
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();

        foreach ($reflectionMethod->getAttributes(FormGetParameter::class) as $attribute) {
            $formGetParameter = $attribute->newInstance();

            $this->processFilterFormAnnotation($formGetParameter, $api, $route);
        }
    }

    private function processFilterFormAnnotation(FormGetParameter $annotation, OpenApi $api, Route $route): void
    {
        $filterForm = $this->formFactory->create($annotation->formType);

        $operations = $this->getOperations($api, $route);

        foreach ($operations as $operation) {
            foreach ($filterForm->all() as $formItem) {
                $name = '';
                if ($formItem->getParent() && $formItem->getParent()->getName()) {
                    $name = $formItem->getParent()->getName();
                }
                $this->describeFormItem($name, $operation, $formItem);
            }
        }
    }

    private function describeFormItem(string $prefix, Operation $operation, FormInterface $formItem): void
    {
        $name = $prefix . "[" . $formItem->getName() . "]";

        if ($formItem->count() === 0) {
            $this->prepareParameter($operation, $name, $formItem);
        }
        else {
            foreach ($formItem as $child) {
                $this->describeFormItem($name, $operation, $child);
            }
        }
    }

    public function prepareParameter(Operation $operation, string $name, FormInterface $formItem): void
    {
        $formType = $formItem->getConfig()->getType()->getInnerType();
        $isCollection = $formType instanceof CollectionType
            || ($formType instanceof ChoiceType && $formItem->getConfig()->getOption('multiple'))
            || ($formType instanceof Symfony\Bridge\Doctrine\Form\Type\EntityType && $formItem->getConfig()->getOption('multiple'))
        ;
        if ($isCollection) {
            $name .= '[]';
        }

        $parameter = Util::getOperationParameter($operation, $name, 'query');
        $parameter->required = $formItem->getConfig()->getRequired();

        $formItemDocumentation = $formItem->getConfig()->getOption('documentation');
        if ($formItemDocumentation) {
            $parameter->mergeProperties($formItemDocumentation);
        }

        /** @var Schema $schema */
        $schema = Util::getChild($parameter, Schema::class);
        $this->fillSchemaForFormItem($schema, $formItem);

        try {
            $reflection = new ReflectionProperty($formItem->getParent()->getConfig()->getDataClass(), $formItem->getName());
            $docBlock = $this->docBlockFactory->create($reflection);

            $title = $docBlock->getSummary();

            /** @var Var_ $var */
            foreach ($docBlock->getTagsByName('var') as $var) {
                if ('' === $title && method_exists($var, 'getDescription') && null !== $description = $var->getDescription()) {
                    $title = $description->render();
                }
            }

            if ($title !== '') {
                $parameter->description = $title;
            }
            if ($docBlock->getDescription()->render() !== '') {
                $parameter->description = $docBlock->getDescription()->render();
            }
        } catch (Exception) {}
    }

    private function fillSchemaForFormItem(Schema $schema, FormInterface $formItem): void
    {
        $formType = $formItem->getConfig()->getType()->getInnerType();

        if ($formType instanceof DateType) {
            $schema->type = 'string';
            $schema->format = 'date';
        }
        else if ($formType instanceof DateTimeType) {
            $schema->type = 'string';
            $schema->format = 'date-time';
        }
        else if ($formType instanceof NumberType) {
            $schema->type = 'number';
        }
        else if ($formType instanceof IntegerType) {
            $schema->type = 'integer';
        }
        else if ($formType instanceof CheckboxType) {
            $schema->type = 'boolean';
        }
        else if ($formType instanceof CollectionType) {
            $schema->type = 'array';

            $innerType = match ($formItem->getConfig()->getOption('entry_type')) {
                NumberType::class => 'number',
                IntegerType::class, Symfony\Bridge\Doctrine\Form\Type\EntityType::class => 'integer',
                default => 'string',
            };

            $schema->items = [
                'type' => $innerType,
                'required' => true,
            ];
        }
        else if ($formType instanceof ChoiceType) {
            $multiple = $formItem->getConfig()->getOption('multiple');
            if ($multiple) {
                $schema->type = 'array';
                $schema->items = [
                    'type' => 'string',
                ];
            }
            else {
                $schema->type = 'string';
            }

            $choices = $formItem->getConfig()->getOption('choices');
            if (count($choices) > 0) {
                $choices = array_values($choices);

                if ($multiple) {
                    $schema->items = [
                        'type' => 'string',
                        'enum' => $choices,
                    ];
                } else {
                    $schema->enum = $choices;
                }
            }
        }
        else if ($formType instanceof Symfony\Bridge\Doctrine\Form\Type\EntityType) {
            $multiple = $formItem->getConfig()->getOption('multiple');
            if ($multiple) {
                $schema->type = 'array';
                $schema->items = [
                    'type' => 'integer',
                ];
            }
            else {
                $schema->type = 'integer';
            }
        }
        else {
            $schema->type = 'string';
        }
    }
}
