<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Controller\Controller;
use Kuusamo\Vle\Entity\AwardingBody;
use Kuusamo\Vle\Helper\Form\Select;
use Kuusamo\Vle\Validation\AwardingBodyValidator;
use Kuusamo\Vle\Validation\ValidationException;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

class AwardingBodiesController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $bodies = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\AwardingBody')->findBy([], ['name' => 'ASC']);

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/index.html', [
            'bodies' => $bodies
        ]);
    }

    public function create(Request $request, Response $response)
    {
        $body = new AwardingBody;

        if ($request->isPost()) {
            $logo = $request->getParam('logo') ? $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $request->getParam('logo')) : null;

            $body->setName($request->getParam('name'));
            $body->setLogo($logo);
            $body->setAuthoriserName($request->getParam('authoriserName'));
            $body->setAuthoriserSignature($request->getParam('authoriserSignature'));
            $body->setAuthoriserRole($request->getParam('authoriserRole'));

            try {
                $validator = new AwardingBodyValidator;
                $validator($body);

                $this->ci->get('db')->persist($body);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Awarding body created successfully', true);
                return $response->withRedirect('/admin/awarding-bodies', 303);
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            }
        }

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/create.html', [
            'body' => $body
        ]);
    }

    public function view(Request $request, Response $response, array $args = [])
    {
        $body = $this->ci->get('db')->find('Kuusamo\Vle\Entity\AwardingBody', $args['id']);

        if ($body === null) {
            throw new HttpNotFoundException($request, $response);
        }

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/view.html', [
            'body' => $body
        ]);
    }

    public function accreditations(Request $request, Response $response, array $args = [])
    {
        $body = $this->ci->get('db')->find('Kuusamo\Vle\Entity\AwardingBody', $args['id']);

        if ($body === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            switch ($request->getParam('action')) {
                case 'add':
                    $accreditation = $this->ci->get('db')->find('Kuusamo\Vle\Entity\AwardingBody', $request->getParam('id'));
                    $body->getAccreditations()->add($accreditation);
                    $this->ci->get('db')->persist($body);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('Accreditation added successfully');
                    break;
                case 'remove':
                    foreach ($body->getAccreditations() as $key => $val) {
                        if ($val->getId() == $request->getParam('id')) {
                            unset($body->getAccreditations()[$key]);
                            break;
                        }
                    }

                    $this->ci->get('db')->persist($body);
                    $this->ci->get('db')->flush();
                    $this->alertSuccess('Accreditation removed successfully');
                    break;
            }
        }

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/accreditations.html', [
            'body' => $body,
            'bodyList' => $this->awardingBodyDropdown($body)
        ]);
    }

    private function awardingBodyDropdown(AwardingBody $bodyx)
    {
        $awardingBody = new Select;

        $bodies = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\AwardingBody')->findBy([], ['name' => 'ASC']);
        foreach ($bodies as $body) {
            $awardingBody->addOption($body->getId(), $body->getName());
        }

        return $awardingBody();
    }

    public function edit(Request $request, Response $response, array $args = [])
    {
        $body = $this->ci->get('db')->find('Kuusamo\Vle\Entity\AwardingBody', $args['id']);

        if ($body === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            $logo = $request->getParam('logo') ? $this->ci->get('db')->find('Kuusamo\Vle\Entity\Image', $request->getParam('logo')) : null;

            $body->setName($request->getParam('name'));
            $body->setLogo($logo);
            $body->setAuthoriserName($request->getParam('authoriserName'));
            $body->setAuthoriserSignature($request->getParam('authoriserSignature'));
            $body->setAuthoriserRole($request->getParam('authoriserRole'));

            try {
                $validator = new AwardingBodyValidator;
                $validator($body);

                $this->ci->get('db')->persist($body);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Awarding body updated successfully');
            } catch (ValidationException $e) {
                $this->alertDanger($e->getMessage());
            }
        }

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/edit.html', [
            'body' => $body
        ]);
    }

    public function delete(Request $request, Response $response, array $args = [])
    {
        $body = $this->ci->get('db')->find('Kuusamo\Vle\Entity\AwardingBody', $args['id']);

        if ($body === null) {
            throw new HttpNotFoundException($request, $response);
        }

        if ($request->isPost()) {
            try {
                $this->ci->get('db')->remove($body);
                $this->ci->get('db')->flush();

                $this->alertSuccess('Awarding body deleted successfully', true);
                return $response->withRedirect('/admin/awarding-bodies', 303);
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->alertDanger('You must remove all courses and accreditees first');
            }
        }

        $this->ci->get('meta')->setTitle('Awarding Bodies - Admin');

        return $this->renderPage($request, $response, 'admin/awarding-bodies/delete.html', [
            'body' => $body
        ]);
    }
}
