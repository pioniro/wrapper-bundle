<?php

declare(strict_types=1);

namespace Pioniro\WrapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wrapper');

        $rootNode = method_exists(TreeBuilder::class, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('wrapper');

        $rootNode->children()
            ->booleanNode('enabled')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}
