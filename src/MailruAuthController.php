<?php

namespace Dem13n\Auth\Mailru;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Aego\OAuth2\Client\Provider\Mailru;
use Aego\OAuth2\Client\Provider\MailruResourceOwner;

class MailruAuthController implements RequestHandlerInterface
{
    protected $response;
    protected $settings;
    protected $url;

    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url = $url;
    }

    public function handle(Request $request): ResponseInterface
    {
        $redirectUri = $this->url->to('forum')->route('auth.mailru');

        $provider = new Mailru([
            'clientId' => $this->settings->get('dem13n-auth-mailru.app_id'),
            'clientSecret' => $this->settings->get('dem13n-auth-mailru.app_secret'),
            'redirectUri' => $redirectUri,
            ]);

        $session = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();

        $code = array_get($queryParams, 'code');

        if (! $code) {
            $authUrl = $provider->getAuthorizationUrl();
            $session->put('oauth2state', $provider->getState());

            return new RedirectResponse($authUrl.'&display=popup');
        }

        $state = array_get($queryParams, 'state');

        if (! $state || $state !== $session->get('oauth2state')) {
            $session->remove('oauth2state');

            throw new Exception('Invalid state');
        }

        $token = $provider->getAccessToken('authorization_code', compact('code'));

        $user = $provider->getResourceOwner($token);

        return $this->response->make(
            'mailru',
            $user->getId(),
            function (Registration $registration) use ($user) {
                $registration
                    ->provideTrustedEmail($user->getEmail())
                    ->provideAvatar(array_get($user->toArray(), 'pic_128'))
                    ->suggestUsername($user->getName())
                    ->setPayload($user->toArray());
            }
        );
    }
}
