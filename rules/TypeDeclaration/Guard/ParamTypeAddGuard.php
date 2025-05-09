<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Guard;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitor;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class ParamTypeAddGuard
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function isLegal(Param $param, ClassMethod $classMethod): bool
    {
        $paramName = $this->nodeNameResolver->getName($param->var);
        if ($paramName === null) {
            return false;
        }

        $isLegal = true;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $classMethod->stmts,
            function (Node $subNode) use (&$isLegal, $paramName): ?int {
                if ($subNode instanceof Assign && $subNode->var instanceof Variable && $this->nodeNameResolver->isName(
                    $subNode->var,
                    $paramName
                )) {
                    $isLegal = false;
                    return NodeVisitor::STOP_TRAVERSAL;
                }

                if ($subNode instanceof If_ && (bool) $this->betterNodeFinder->findFirst(
                    $subNode->cond,
                    fn (Node $node): bool => $node instanceof Variable && $this->nodeNameResolver->isName(
                        $node,
                        $paramName
                    )
                )) {
                    $isLegal = false;
                    return NodeVisitor::STOP_TRAVERSAL;
                }

                if ($subNode instanceof Ternary && (bool) $this->betterNodeFinder->findFirst(
                    $subNode,
                    fn (Node $node): bool => $node instanceof Variable && $this->nodeNameResolver->isName(
                        $node,
                        $paramName
                    )
                )) {
                    $isLegal = false;
                    return NodeVisitor::STOP_TRAVERSAL;
                }

                return null;
            }
        );

        return $isLegal;
    }
}
