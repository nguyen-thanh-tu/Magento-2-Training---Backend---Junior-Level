<?php

namespace TUTJunior\CourseType\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;

class Fields extends AbstractModifier
{
    private $locator;

    protected $jsonSerializer;

    public function __construct(
        LocatorInterface $locator,
        \Magento\Framework\Serialize\SerializerInterface $jsonSerializer
    ) {
        $this->locator = $locator;
        $this->jsonSerializer = $jsonSerializer;
    }
    public function modifyData(array $data)
    {
        $product = reset($data);
        if(!empty($data[key($data)]['product']['course_timeline']))
        {
            $data[key($data)]['product']['course_timeline'] = $this->jsonSerializer->unserialize($product['product']['course_timeline']);
        }
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        if ($this->locator->getProduct()->getTypeId() === 'course_type') {
            $meta = array_replace_recursive(
                $meta,
                [
                    'course' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Course Fields'),
                                    'collapsible' => true,
                                    'opened' => true,
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => 'data.product',
                                ],
                            ],
                        ],
                        'children' => $this->getFields()
                    ],
                ]
            );
        }
        return $meta;
    }

    protected function getFields()
    {
        return [
            'document' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Document'),
                            'component'     => 'TUTJunior_CourseType/js/document/document',
                            'elementTmpl'   => 'TUTJunior_CourseType/form/element/document',
                            'componentType' => Field::NAME,
                            'formElement'   => Input::NAME,
                            'dataScope'     => 'document',
                            'dataType'      => Text::NAME,
                            'sortOrder'     => 20
                        ],
                    ],
                ]
            ],
            'course_timeline' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Course Timeline'),
                            'componentType' => 'dynamicRows',
                            'renderDefaultRecord' => false,
                            'recordTemplate' => 'record',
                            'dataScope' => 'course_timeline',
                            'dndConfig' => [
                                'enabled' => false,
                            ],
                            'disabled' => false,
                            'sortOrder' => 10
                        ],
                    ],
                ],
                'children' => [
                    'record' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'componentType' => \Magento\Ui\Component\Container::NAME,
                                    'isTemplate' => true,
                                    'is_collection' => true,
                                    'component' => 'Magento_Ui/js/dynamic-rows/record',
                                    'dataScope' => '',
                                    'require' => '0',
                                ],
                            ],
                        ],
                        'children' => [
                            'from' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => 'date',
                                            'componentType' => Field::NAME,
                                            'dataType' => 'string',
                                            'label' => __('From'),
                                            'dataScope' => 'from',
                                            'require' => '1',
                                            'options' =>[
                                                'date_format' => 'yyyy-MM-dd',
                                                'time_format' => 'hh:mm:ss',
                                                'showsTime' => true,
                                                'timeOnly'=> true
                                            ],
                                            'storeTimeZone' => 'string'
                                        ],
                                    ],
                                ],
                            ],

                            'to' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => 'date',
                                            'componentType' => Field::NAME,
                                            'dataType' => 'string',
                                            'label' => __('To'),
                                            'dataScope' => 'to',
                                            'require' => '1',
                                            'options' =>[
                                                'date_format' => 'yyyy-MM-dd',
                                                'time_format' => 'hh:mm:ss',
                                                'showsTime' => true,
                                                'timeOnly'=> true
                                            ],
                                            'storeTimeZone' => 'string'
                                        ],
                                    ],
                                ],
                            ],

                            'quantity' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'formElement' => Input::NAME,
                                            'componentType' => Field::NAME,
                                            'dataType' => 'string',
                                            'label' => __('Quantity'),
                                            'dataScope' => 'quantity',
                                            'require' => '1',
                                        ],
                                    ],
                                ],
                            ],

                            'actionDelete' => [
                                'arguments' => [
                                    'data' => [
                                        'config' => [
                                            'componentType' => 'actionDelete',
                                            'dataType' => Text::NAME,
                                        ],
                                    ],
                                ],
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }
}

