<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Controller;

use OpenApi\Attributes as OA;
use Sandronimus\NelmioFormGetParameterPluginBundle\Attribute\FormGetParameter;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form\OneLevelFormType;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form\ThreeLevelFormType;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form\TwoLevelFormType;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\OneLevelModel;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\ThreeLevelModel;
use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\TwoLevelModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[OA\Get(summary: "test")]
    #[FormGetParameter(OneLevelFormType::class)]
    #[FormGetParameter(TwoLevelFormType::class)]
    #[FormGetParameter(ThreeLevelFormType::class)]
    #[Route("/api/test", name: 'test', methods: ['GET'])]
    public function testAction(Request $request)
    {
        $oneLevelModel = new OneLevelModel();
        $oneLevelForm = $this->createForm(OneLevelFormType::class, $oneLevelModel);

        $twoLevelModel = new TwoLevelModel();
        $twoLevelForm = $this->createForm(TwoLevelFormType::class, $twoLevelModel);

        $threeLevelModel = new ThreeLevelModel();
        $threeLevelForm = $this->createForm(ThreeLevelFormType::class, $threeLevelModel);

        $oneLevelForm->handleRequest($request);
        if ($oneLevelForm->isSubmitted() && !$oneLevelForm->isValid()) {
            return new Response(status: 400);
        }

        $twoLevelForm->handleRequest($request);
        if ($twoLevelForm->isSubmitted() && !$twoLevelForm->isValid()) {
            return new Response(status: 400);
        }

        $threeLevelForm->handleRequest($request);
        if ($threeLevelForm->isSubmitted() && !$threeLevelForm->isValid()) {
            return new Response(status: 400);
        }

        return new JsonResponse([
            'oneLevel' => $oneLevelModel,
            'twoLevel' => $twoLevelModel,
            'threeLevel' => $threeLevelModel,
        ]);
    }
}
