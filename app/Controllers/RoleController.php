<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Role;


class RoleController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Role';
        $model = new Role();
        $data['roles'] = $model->findAll();
        return view('master-data/roles/index', $data);
    }

    public function create()
    {
        session();
        $data['title'] = 'Create Role';
        $data['validation'] = \Config\Services::validation();
        return view('master-data/roles/create', $data);
    }

    public function store() {

        
         //define validation
         $validationRules = $this->validate([
            'name'    =>'required|min_length[3]',
        ]);

        if (!$validationRules) {
            $validation = \Config\Services::validation();
            // Redirect back to the edit form with the ID
            return redirect()->to("/role/create")->withInput();
        }
        
        $model = new Role();
        $data = [
            'name' => $this->request->getPost('name'),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $model->insert($data);

        session()->setFlashdata('message', 'Record has been created successfully.');

        // Redirect to a different page or list view
        return redirect()->to('/role');
        
    }

    public function edit($id) {
        $data['title'] = 'Edit Role';
        $model = new Role();
        $data['role'] = $model->where('id',$id)->first();
        return view('master-data/roles/edit', $data);
    }

    public function update($id) {

        //define validation
        $validationRules = $this->validate([
            'name'    =>'required|min_length[3]',
        ]);

        if (!$validationRules) {
            $validation = \Config\Services::validation();
            // Redirect back to the edit form with the ID
            return redirect()->to("/role/edit/$id")->withInput();
        }

        $model = new Role();
        $data = [
            'name' => $this->request->getPost('name'),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $model->update($id, $data);

        session()->setFlashdata('message', 'Record has been updated successfully.');

        // Redirect to a different page or list view
        return redirect()->to('/role');
    }

    public function delete($id) {

        $model = new Role();

        if ($model->delete($id)) {
            $response = [
                'success' => true,
                'message' => 'Record has been deleted successfully.',
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Failed to delete the record.',
            ];
        }

        // Set the flash message for the session
        session()->setFlashdata('message', $response['message']);

        // Return a JSON response
        return $this->response->setJSON($response);
    }


}
