<?php

namespace Classes\Controllers;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TicketsController extends Controller
{
    /**
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function index(Request $request, Response $response): ResponseInterface
    {
        $sql = 'SELECT * FROM tickets';
        $stmt = $this->db->query($sql);
        $tickets = [];
        while ($row = $stmt->fetch()) {
            $tickets[] = $row;
        }
        $data = ['tickets' => $tickets];
        return $this->renderer->render($response, 'tickets/index.phtml', $data);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return ResponseInterface
     */
    public function create(Request $request, Response $response, array $args): ResponseInterface
    {
        $args['csrf_name'] = $request->getAttribute('csrf_name');
        $args['csrf_value'] = $request->getAttribute('csrf_value');
        return $this->renderer->render($response, 'tickets/create.phtml', $args);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function store(Request $request, Response $response): ResponseInterface
    {
        if ($request->getAttribute('has_errors')) {
            $args['errors'] = $request->getAttribute('errors');
            return $this->create($request, $response, $args);
        }

        $subject = $request->getParsedBodyParam('subject');
        $sql = 'INSERT INTO tickets (subject) values (:subject)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['subject' => $subject]);
        return $response->withRedirect("/tickets");
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return ResponseInterface
     */
    public function show(Request $request, Response $response, array $args): ResponseInterface
    {
        $args['csrf_name'] = $request->getAttribute('csrf_name');
        $args['csrf_value'] = $request->getAttribute('csrf_value');

        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write('not found');
        }
        $data = array_merge(['ticket' => $ticket], $args);
        return $this->renderer->render($response, 'tickets/show.phtml', $data);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return ResponseInterface
     */
    public function edit(Request $request, Response $response, array $args): ResponseInterface
    {
        $args['csrf_name'] = $request->getAttribute('csrf_name');
        $args['csrf_value'] = $request->getAttribute('csrf_value');

        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write('not found');
        }
        $data = array_merge(['ticket' => $ticket], $args);
        return $this->renderer->render($response, 'tickets/edit.phtml', $data);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return ResponseInterface
     */
    public function update(Request $request, Response $response, array $args): ResponseInterface
    {
        if ($request->getAttribute('has_errors')) {
            $args['errors'] = $request->getAttribute('errors');
            return $this->edit($request, $response, $args);
        }

        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write('not found');
        }
        $ticket['subject'] = $request->getParsedBodyParam('subject');
        $stmt = $this->db->prepare('UPDATE tickets SET subject = :subject WHERE id = :id');
        $stmt->execute($ticket);
        return $response->withRedirect("/tickets");
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return ResponseInterface
     */
    public function delete(Request $request, Response $response, array $args): ResponseInterface
    {
        if ($request->getAttribute('has_errors')) {
            $args['errors'] = $request->getAttribute('errors');
            return $this->show($request, $response, $args);
        }

        try {
            $ticket = $this->fetchTicket($args['id']);
        } catch (\Exception $e) {
            return $response->withStatus(404)->write('not found');
        }
        $stmt = $this->db->prepare('DELETE FROM tickets WHERE id = :id');
        $stmt->execute(['id' => $ticket['id']]);
        return $response->withRedirect("/tickets");
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    private function fetchTicket(int $id): array
    {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            throw new \Exception('not found');
        }
        return $ticket;
    }
}
