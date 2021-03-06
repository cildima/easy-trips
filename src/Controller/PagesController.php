<?php

namespace App\Controller;

use App\Entity\PageTranslations;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends TranslatableController
{
    /**
     *
     * @param mixed $object
     * @param Request $request
     *
     * @return null
     */
    protected function preCreate(Request $request, $object)
    {

        $langs = $request->request->get("langs");

        if (!empty($langs)) {
            $this->translationsOnCreate($langs, $object, PageTranslations::class);
        }

        return null;
    }

    /**
     *
     * @param mixed $object
     * @param Request $request
     *
     * @return null
     */
    protected function preEdit(Request $request, $object)
    {
        $langs = $request->request->get("langs");
        $this->translationsOnEdit($langs, $object, PageTranslations::class);

        return null;
    }

    public function listAction()
    {
        $request = $this->getRequest();
        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }
        $listMode = $this->admin->getListMode();

        if ($listMode === 'tree') {
            $this->admin->showMosaicButton(false);
            $this->admin->checkAccess('list');

            $preResponse = $this->preList($request);
            if ($preResponse !== null) {
                return $preResponse;
            }

            return $this->renderWithExtraParams(
                'admin/tree.html.twig',
                [
                    'action' => 'list',
                    'csrf_token' => $this->getCsrfToken('sonata.batch'),
                    '_sonata_admin' => $request->get('_sonata_admin'),
                ],
                null
            );
        }

        return parent::listAction();
    }

    public function treeDataAction()
    {
        $request = $this->getRequest();
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $operation = $request->get('operation');
        switch ($operation) {
            case 'get_node':
                $nodeId = $request->get('id');
                if ($nodeId) {
                    $parentNode = $em->getRepository($this->admin->getClass())->find($nodeId);
                    $nodes = $em->getRepository($this->admin->getClass())->getChildren($parentNode, true);
                } else {
                    $nodes = $em->getRepository($this->admin->getClass())->getRootNodes();
                }

                $nodes = array_map(
                    function ($node) {
                        return [
                            'id' => $node->getId(),
                            'text' => (string) $node,
                            'children' => true,
                        ];
                    },
                    $nodes
                );

                return new JsonResponse($nodes);
            case 'rename_node':
                $nodeId = $request->get('id');
                $nodeText = $request->get('text');
                $node = $em->getRepository($this->admin->getClass())->find($nodeId);

                $node->{'set'.ucfirst($this->admin->getTreeTextField())}($nodeText);
                $this->admin->getModelManager()->update($node);

                return new JsonResponse([
                    'id' => $node->getId(),
                    'text' => $node->{'get'.ucfirst($this->admin->getTreeTextField())}()
                ]);
            case 'create_node':
                $parentNodeId = $request->get('parent_id');
                $parentNode = $em->getRepository($this->admin->getClass())->find($parentNodeId);
                $nodeText = $request->get('text');
                $node = $this->admin->getNewInstance();
                $node->{'set'.ucfirst($this->admin->getTreeTextField())}($nodeText);
                $node->setParent($parentNode);
                $this->admin->getModelManager()->create($node);

                return new JsonResponse([
                    'id' => $node->getId(),
                    'text' => $node->{'get'.ucfirst($this->admin->getTreeTextField())}()
                ]);
            case 'delete_node':
                $nodeId = $request->get('id');
                $node = $em->getRepository($this->admin->getClass())->find($nodeId);
                $this->admin->getModelManager()->delete($node);

                return new JsonResponse();

            case 'move_node':

                $repository = $em->getRepository($this->admin->getClass());

                $nodeId = $request->get('id');
                $nodePosition = $request->get('position');
                $nodeOldPosition = $request->get('old_position');
                $parentId = $request->get('parent');

                $node = $repository->find($nodeId);
                $parent = $repository->find($parentId);

                if ($nodePosition > 0) {
                    $children = $repository->children($parent, true);
                    if ($nodePosition < $nodeOldPosition) {
                        $nodePosition--;
                    }
                    $repository->persistAsNextSiblingOf($node, $children[$nodePosition]);

                } else {
                    $repository->persistAsFirstChildOf($node, $parent);
                }

                $em->flush();

                return new JsonResponse([
                    'id' => $request->get('id'),
                    'parent' => $request->get('parent'),
                    'position' => $request->get('position'),
                ]);
        }

        throw new BadRequestHttpException('Unknown action for tree');
    }
}