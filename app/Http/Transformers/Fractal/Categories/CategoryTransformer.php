<?php

namespace GetCandy\Http\Transformers\Fractal\Categories;

use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Http\Transformers\Fractal\Assets\AssetTransformer;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeGroupTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Routes\RouteTransformer;

class CategoryTransformer extends BaseTransformer
{
    protected $attributeGroups;

    protected $defaultIncludes = [
        'routes'
    ];
    protected $availableIncludes = [
        'attribute_groups','assets', 'children'
    ];

    public function transform(Category $category)
    {
        $data = [
            'id' => $category->encodedId(),
            'attribute_data' => $category->attribute_data,
            'depth' => $category->depth,
            'product_count' => $category->getProductCount(),
            'lazy' => $category->hasChildren(),
            'thumbnail' => $this->getThumbnail($category),
            'test' => 1
        ];
/*
        $children = [];

        $i = 0;
        while ($category->children->count() > $i) {
            $transformer = $this->item($category->children->offsetGet($i), new CategoryTransformer);
            $children[] = app()->fractal->createData($transformer)->toArray();
            $i++;
        }

        $data['children'] = $children;
*/
        return $data;
    }
    public function includeChildren(Category $category)
    {
        return $this->collection($category->children, $this);
    }
    public function includeRoutes(Category $category)
    {
        return $this->collection($category->routes, new RouteTransformer);
    }

    public function includeAttributeGroups(Category $category)
    {
        return $this->collection([], new AttributeGroupTransformer);
    }

    public function includeAssets(Category $category)
    {
        return $this->collection($category->assets()->orderBy('position', 'asc')->get(), new AssetTransformer);
    }
}