<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Module;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ModulesAjaxController extends AdminController
{
    public function create(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if (!$course) {
            return $this->badRequest($response, 'Course not found');
        }

        $module = new Module;
        $module->setCourse($course);
        $module->setName($json['name']);
        $module->setDescription($json['description']);
        $module->setStatus($json['status']);

        if ($module->getName() == '') {
            return $this->badRequest($response, 'Module name not provided');
        }

        $lastModule = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Module')->findOneBy(['course' => $course], ['priority' => 'DESC']);
        $priority = ($lastModule) ? ($lastModule->getPriority() + 1) : 1;
        $module->setPriority($priority);

        $this->ci->get('db')->persist($module);
        $this->ci->get('db')->flush();

        return $this->success($response, $module);
    }

    public function retrieve(Request $request, Response $response, $args)
    {
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if (!$course) {
            return $this->badRequest($response, 'Course not found');
        }

        return $this->success($response, $course->getModules()->toArray());
    }

    public function update(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $course = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Course', $args['id']);

        if (!$course) {
            return $this->badRequest($response, 'Course not found');
        }

        $priority = 1;

        foreach ($json['order'] as $id) {
            $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $id);
            $module->setPriority($priority);
            $this->ci->get('db')->persist($module);
            $priority++;
        }

        $this->ci->get('db')->flush();

        return $this->success($response);
    }

    public function updateModule(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $args['id']);

        if (!$module) {
            return $this->badRequest($response, 'Module not found');
        }

        $module->setName($json['name']);
        $module->setDescription($json['description']);
        $module->setStatus($json['status']);
        $module->setDelay($json['delay']);

        if ($module->getName() == '') {
            return $this->badRequest($response, 'Module name not provided');
        }

        $this->ci->get('db')->persist($module);
        $this->ci->get('db')->flush();

        return $this->success($response, $module);
    }

    public function deleteModule(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $args['id']);

        if (!$module) {
            return $this->badRequest($response, 'Module not found');
        }

        $this->ci->get('db')->remove($module);
        $this->ci->get('db')->flush();

        return $this->success($response);
    }

    public function updateModuleLessons(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $args['id']);

        if (!$module) {
            return $this->badRequest($response, 'Module not found');
        }

        $priority = 1;

        foreach ($json['order'] as $id) {
            $lesson = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Lesson', $id);
            $lesson->setPriority($priority);
            $this->ci->get('db')->persist($lesson);
            $priority++;
        }

        $this->ci->get('db')->flush();

        return $this->success($response);
    }
}
