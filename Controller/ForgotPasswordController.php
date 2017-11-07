<?php

/*
 * This file is part of the CoopTilleulsForgotPasswordBundle package.
 *
 * (c) Vincent Chalamon <vincent@les-tilleuls.coop>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoopTilleuls\ForgotPasswordBundle\Controller;

use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use CoopTilleuls\ForgotPasswordBundle\Manager\ForgotPasswordManager;
use CoopTilleuls\ForgotPasswordBundle\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
final class ForgotPasswordController
{
    private $forgotPasswordManager;
    private $normalizer;
    private $groups;

    /**
     * @param ForgotPasswordManager $forgotPasswordManager
     * @param NormalizerInterface   $normalizer
     * @param array                 $groups
     */
    public function __construct(
        ForgotPasswordManager $forgotPasswordManager,
        NormalizerInterface $normalizer,
        array $groups
    ) {
        $this->forgotPasswordManager = $forgotPasswordManager;
        $this->normalizer = $normalizer;
        $this->groups = $groups;
    }

    /**
     * @param $propertyName
     * @param $value
     * @return Response
     */
    public function resetPasswordAction($propertyName, $value)
    {
        $this->forgotPasswordManager->resetPassword($propertyName, $value);

        return new Response('', 204);
    }

    /**
     * @param AbstractPasswordToken $token
     *
     * @return JsonResponse
     */
    public function getTokenAction(AbstractPasswordToken $token)
    {
        return new JsonResponse(
            $this->normalizer->normalize($token, 'json', $this->groups ? ['groups' => $this->groups] : [])
        );
    }

    /**
     * @param AbstractPasswordToken $token
     * @param string                $password
     *
     * @return Response
     */
    public function updatePasswordAction(AbstractPasswordToken $token, $password)
    {
        $this->forgotPasswordManager->updatePassword($token, $password);

        return new Response('', 204);
    }
}
