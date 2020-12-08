<?php

namespace Kuusamo\Vle\Controller\Admin;

use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Helper\Block\BlockFactory;
use Kuusamo\Vle\Helper\Block\HydratorFactory;
use Kuusamo\Vle\Helper\Block\ValidationException;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LessonsAjaxController extends AdminController
{
    public function create(Request $request, Response $response)
    {
        $json = $request->getParsedBody();
        $module = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Module', $json['moduleId']);

        if (!$module) {
            return $this->badRequest($response, 'Module not found');
        }

        $lesson = new Lesson;
        $lesson->setCourse($module->getCourse());
        $lesson->setModule($module);
        $lesson->setName($json['name']);
        $lesson->setStatus($json['status']);

        if ($lesson->getName() == '') {
            return $this->badRequest($response, 'Lesson name not provided');
        }

        $lastLesson = $this->ci->get('db')->getRepository('Kuusamo\Vle\Entity\Lesson')->findOneBy(['module' => $module], ['priority' => 'DESC']);
        $priority = ($lastLesson) ? ($lastLesson->getPriority() + 1) : 1;
        $lesson->setPriority($priority);

        $this->ci->get('db')->persist($lesson);
        $this->ci->get('db')->flush();

        return $this->success($response, $lesson);
    }

    public function update(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $lesson = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Lesson', $args['id']);

        if (!$lesson) {
            return $this->badRequest($response, 'Lesson not found');
        }

        $lesson->setName($json['name']);
        $lesson->setStatus($json['status']);
        $lesson->setMarking($json['marking']);
        $lesson->setPassMark($json['passMark']);

        if ($lesson->getName() == '') {
            return $this->badRequest($response, 'Lesson name not provided');
        }

        $this->ci->get('db')->persist($lesson);
        $this->ci->get('db')->flush();

        return $this->success($response, $lesson);
    }

    public function updateBlocks(Request $request, Response $response, $args)
    {
        $json = $request->getParsedBody();
        $lesson = $this->ci->get('db')->find('Kuusamo\Vle\Entity\Lesson', $args['id']);

        if (!$lesson) {
            return $this->badRequest($response, 'Lesson not found');
        }

        // reset the priorities
        $dql = "UPDATE Kuusamo\Vle\Entity\Block\Block b
                SET b.priority = 0
                WHERE b.lesson = :lesson";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('lesson', $lesson);
        $query->getResult();

        $priority = 1;

        foreach ($json['blocks'] as $block) {
            if (isset($block['id'])) {
                $blockObj = $this->ci->get('db')->find(
                    'Kuusamo\Vle\Entity\Block\Block',
                    $block['id']
                );
            } else {
                $blockObj = BlockFactory::create($block['type']);
                $blockObj->setLesson($lesson);
            }

            $hydrator = HydratorFactory::create($block['type'], $this->ci->get('db'));
            $hydrator->hydrate($blockObj, $block);
            $blockObj->setPriority($priority);

            try {
                $hydrator->validate($blockObj);
            } catch (ValidationException $e) {
                return $this->badRequest($response, sprintf(
                    'Validation failed (%s): %s',
                    $block['type'],
                    $e->getMessage()
                ));
            }

            $this->ci->get('db')->persist($blockObj);

            $priority++;
        }

        $this->ci->get('db')->flush();

        // delete any blocks that aren't in the new version
        $dql = "DELETE Kuusamo\Vle\Entity\Block\Block b
                WHERE b.lesson = :lesson
                AND b.priority = 0";
        $query = $this->ci->get('db')->createQuery($dql);
        $query->setParameter('lesson', $lesson);
        $query->getResult();

        return $this->success($response, [
            'blocks' => $lesson->getBlocks()->toArray()
        ]);
    }
}
