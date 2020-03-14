<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Resource;
use App\Form\ResourceType;
use App\Service\ResourceManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private ResourceManager $resourceManager;

    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    /**
     * @Route("/")
     * @Route("/admin/r/{slug}", name="app_index")
     * @param string|null $slug
     * @return Response
     */
    public function index(string $slug = null): Response
    {
        $currentResource = ($slug !== null) ? $this->resourceManager->getOne($slug) : null;

        return $this->render(
            'admin/admin.html.twig',
            [
                'resources' => $this->resourceManager->getAll(),
                'current'   => $currentResource,
            ]
        );
    }

    /**
     * Handles resource adding
     * @Route("/admin/add-resource", name="app_add-resource")
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function createResource(Request $request): Response
    {
        $form = $this->createForm(ResourceType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'admin/form.html.twig',
                [
                    'form'      => $form->createView(),
                    'resources' => $this->resourceManager->getAll(),
                    'current'   => null,
                ]
            );
        }
        $this->resourceManager->persist($form->getData());

        return $this->redirectToRoute('app_index');
    }

    /**
     * Handles resource edit
     * @Route("/admin/edit/{slug}", name="app_edit-resource")
     *
     * @param Request       $request
     * @param Resource|null $resource
     * @return Response
     * @throws Exception
     */
    public function editResource(Request $request, Resource $resource = null): Response
    {
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'admin/form.html.twig',
                [
                    'form'      => $form->createView(),
                    'resources' => $this->resourceManager->getAll(),
                    'current'   => $resource,
                ]
            );
        }
        $this->resourceManager->persist($resource);

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/admin/remove-resource/{slug}", name="app_resource-delete")
     * @param Resource|null $resource
     * @return Response
     */
    public function removeResource(Resource $resource = null): Response
    {
        if (!$resource instanceof Resource) {
            return $this->redirectToRoute('app_index');
        }

        $this->resourceManager->delete($resource);
        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("admin/settings", name="app_settings")
     */
    public function settings(): Response
    {
        return $this->render('admin/settings.html.twig');
    }
}
