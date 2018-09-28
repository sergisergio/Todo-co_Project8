<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
    }

    /**
     * @Route("/tasks/todo", name="task_list_todo")
     */
    public function listActionTodo()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAllTasksTodo()]);
    }

    /**
     * @Route("/tasks/done", name="task_list_done")
     */
    public function listActionDone()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAllTasksDone()]);
    }

    /**
     * @Route("/tasks/datedesc", name="task_list_datedesc")
     */
    public function listActionByDateDesc()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAllTasksByDateDesc()]);
    }

    /**
     * @Route("/tasks/dateasc", name="task_list_dateasc")
     */
    public function listActionByDateAsc()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAllTasksByDateAsc()]);
    }

    /**
     * @Route("/tasks/author", name="task_list_author")
     */
    public function listActionByAuthor()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAllTasksByAuthor()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $user = $this->getUser();
        $task->setUser($user);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        if ($task->getUser() === $this->getUser()) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }

        return $this->render(
            'task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            ]
        );
        } elseif ($task->getUser() === null && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render(
                'task/edit.html.twig', [
                    'form' => $form->createView(),
                    'task' => $task,
                ]
            );
        } else {
            $this->addFlash('error', 'Vous devez être l\'auteur pour modifier cette tâche !');
            return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
        }
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        if ($task->getUser() === $this->getUser()) {
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            if ($task->isDone() == true) {
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
            } else {
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme étant encore à faire.', $task->getTitle()));
            }
        } elseif ($task->getUser() === null && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            if ($task->isDone() == true) {
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
            } else {
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme étant encore à faire.', $task->getTitle()));
            }
        } else {
            $this->addFlash('error', 'Vous devez être l\'auteur et/ou administrateur pour modifier le statut de cette tâche !');
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if ($task->getUser() === $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } elseif ($task->getUser() === null && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', 'Vous devez être l\'auteur pour supprimer cette tâche !');
        }

        return $this->redirectToRoute('task_list');
    }
}
