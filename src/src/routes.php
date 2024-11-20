<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    require '../app/Scripts/ssp.class.php';

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return $this->view->render($response, 'home.twig', []);
    })->setName('home');

    $app->get('/schools', function (Request $request, Response $response, array $args) use ($container) {
        return $this->view->render($response, 'school.twig', []);
    })->setName('schools');

    $app->group('/api', function () use ($app) {
        $app->get('/schools', function (Request $request, Response $response, array $args) {
            $table = 'tbl_schools';
            $primaryKey = 'id';
            $columns = [
                ['db' => 'id', 'dt' => 0],
                ['db' => 'school_name', 'dt' => 1],
                ['db' => 'address', 'dt' => 2]
            ];

            $sql_details = [
                'user' => 'root',
                'pass' => 'secret',
                'db' => 'educa_db',
                'host' => 'mysql'
            ];

            $data = SSP::simple($request->getQueryParams(), $sql_details, $table, $primaryKey, $columns);

            return $response->withJson($data);
        })->setName('api.schools');

        $app->post('/schools', function (Request $request, Response $response, array $args) {
            $newSchool = $request->getParsedBody();

            $school = $this->db->insert('tbl_schools', [
                'school_name' => $newSchool['school_name'],
                'address' => $newSchool['address'],
            ]);

            if ($school->rowCount() > 0) {
                return $response->withJson([
                    'message' => 'Data sekolah berhasil disimpan'
                ], 201);
            }

            return $response->withJson([
                'message' => 'Data sekolah gagal disimpan'
            ], 400);
        })->setName('api.schools.create');

        $app->get('/schools/{id}', function (Request $request, Response $response, array $args) {
            $school = $this->db->select('tbl_schools', '*', [
                'id' => $args['id']
            ]);

            return $response->withJson($school[0], 200);
        });

        $app->patch('/schools/{id}', function (Request $request, Response $response, array $args) {
            $editSchool = $request->getParsedBody();

            $school = $this->db->update('tbl_schools', [
                'school_name' => $editSchool['school_name'],
                'address' => $editSchool['address']
            ], [
                'id' => $args['id']
            ]);

            if ($school) {
                return $response->withJson([
                    'message' => 'Data sekolah berhasil diubah'
                ], 200);
            }

            return $response->withJson([
                'message' => 'Data sekolah gagal disimpan'
            ], 400);
        })->setName('api.schools.update');

        $app->delete('/schools/{id}', function (Request $request, Response $response, array $args) {
            $this->db->delete('tbl_schools', [
                'id' => $args['id']
            ]);

            return $response->withJson([
                'message' => 'Data sekolah berhasil dihapus'
            ], 200);
        })->setName('api.schools.delete');
    });

    $app->get('/students', function (Request $request, Response $response, array $args) use ($container) {
        $schools = $this->db->select('tbl_schools', ['id', 'school_name']);

        return $this->view->render($response, 'student.twig', [
            'schools' => $schools
        ]);
    })->setName('students');

    $app->group('/api', function () use ($app) {
        $app->get('/students', function (Request $request, Response $response, array $args) {
            $table = 'tbl_students';
            $primaryKey = 'id';
            $columns = [
                ['db' => 'id', 'dt' => 0],
                ['db' => 'school_id', 'dt' => 1],
                ['db' => 'name', 'dt' => 2],
                ['db' => 'email', 'dt' => 3],
            ];

            $sql_details = [
                'user' => 'root',
                'pass' => 'secret',
                'db' => 'educa_db',
                'host' => 'mysql'
            ];

            $data = SSP::simple($request->getQueryParams(), $sql_details, $table, $primaryKey, $columns);

            return $response->withJson($data);
        })->setName('api.students');

        $app->post('/students', function (Request $request, Response $response, array $args) {
            $newStudent = $request->getParsedBody();

            $student = $this->db->insert('tbl_students', [
                'school_id' => $newStudent['school_id'],
                'name' => $newStudent['name'],
                'email' => $newStudent['email'],
            ]);

            if ($student->rowCount() > 0) {
                return $response->withJson([
                    'message' => 'Data siswa berhasil disimpan'
                ], 201);
            }

            return $response->withJson([
                'message' => 'Data siswa gagal disimpan'
            ], 400);
        })->setName('api.students.create');

        $app->get('/students/{id}', function (Request $request, Response $response, array $args) {
            $student = $this->db->select('tbl_students', '*', [
                'id' => $args['id']
            ]);

            return $response->withJson($student[0], 200);
        });

        $app->patch('/students/{id}', function (Request $request, Response $response, array $args) {
            $editStudent = $request->getParsedBody();

            $student = $this->db->update('tbl_students', [
                'school_id' => $editStudent['school_id'],
                'name' => $editStudent['name'],
                'email' => $editStudent['email'],
            ], [
                'id' => $args['id']
            ]);

            if ($student) {
                return $response->withJson([
                    'message' => 'Data siswa berhasil diubah'
                ], 200);
            }

            return $response->withJson([
                'message' => 'Data siswa gagal disimpan'
            ], 400);
        })->setName('api.students.update');

        $app->delete('/students/{id}', function (Request $request, Response $response, array $args) {
            $this->db->delete('tbl_students', [
                'id' => $args['id']
            ]);

            return $response->withJson([
                'message' => 'Data siswa berhasil dihapus'
            ], 200);
        })->setName('api.students.delete');
    });
};
