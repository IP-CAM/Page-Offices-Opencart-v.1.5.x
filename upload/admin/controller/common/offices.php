<?php
class ControllerCommonOffices extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('common/offices');
        $this->load->model('common/offices');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getList();
    }

    public function insert() {
        $this->load->language('common/offices');
        $this->load->model('common/offices');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $office_id = $this->model_common_offices->addOffice($this->request->post);

            $this->session->data['success'] = $this->language->get('text_insert_success');
            if($this->request->post['act_mode']) {
                $this->redirect($this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->redirect($this->url->link('common/offices/update', 'office_id='.$office_id.'&token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->getForm();
    }

    public function update() {
        $this->load->language('common/offices');
        $this->load->model('common/offices');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $office_id = $this->request->get['office_id'];
            $this->model_common_offices->editOffice($office_id, $this->request->post);

            $this->session->data['success'] = $this->language->get('text_update_success');
            if($this->request->post['act_mode']) {
                $this->redirect($this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->redirect($this->url->link('common/offices/update', 'office_id='.$office_id.'&token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('common/offices');
        $this->load->model('common/offices');
        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $office_id) {
                $this->model_common_offices->deleteOffice($office_id);
            }

            $this->session->data['success'] = $this->language->get('text_delete_success');

            $this->redirect($this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->getList();
    }

    private function getList() {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('common/offices/insert', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['delete'] = $this->url->link('common/offices/delete', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['offices'] = array();

        $results = $this->model_common_offices->getOffices(0);
        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('common/offices/update', 'token=' . $this->session->data['token'] . '&office_id=' . $result['office_id'], 'SSL')
            );

            $this->data['offices'][] = array(
                'office_id'   => $result['office_id'],
                'name'        => $result['name'],
                'sort_order'  => $result['sort_order'],
                'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'selected'    => isset($this->request->post['selected']) && in_array($result['office_id'], $this->request->post['selected']),
                'action'      => $action
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_sort_order'] = $this->language->get('column_sort_order');
        $this->data['column_action'] = $this->language->get('column_action');
        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->template = 'common/offices_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function getForm() {
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_default'] = $this->language->get('text_default');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_address'] = $this->language->get('entry_address');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_phone'] = $this->language->get('entry_phone');
        $this->data['entry_fax'] = $this->language->get('entry_fax');
        $this->data['entry_longitude'] = $this->language->get('entry_longitude');
        $this->data['entry_latitude'] = $this->language->get('entry_latitude');
        $this->data['entry_parent'] = $this->language->get('entry_parent');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['button_save_and_close'] = $this->language->get('button_save_and_close');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_data'] = $this->language->get('tab_data');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = array();
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['office_id'])) {
            $this->data['action'] = $this->url->link('common/offices/insert', 'token=' . $this->session->data['token'], 'SSL');
        } else {
            $this->data['action'] = $this->url->link('common/offices/update', 'token=' . $this->session->data['token'] . '&office_id=' . $this->request->get['office_id'], 'SSL');
        }

        $this->data['cancel'] = $this->url->link('common/offices', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->get['office_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $office_info = $this->model_common_offices->getOffice($this->request->get['office_id']);
        }

        $this->data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['office_description'])) {
            $this->data['office_name'] = $this->request->post['office_description'];
        } elseif (isset($this->request->get['office_id'])) {
            $this->data['office_description'] = $this->model_common_offices->getOfficeDescriptions($this->request->get['office_id']);
        } else {
            $this->data['office_description'] = array();
        }

        $offices = $this->model_common_offices->getOffices(0);

        // Remove own id from list
        if (!empty($office_info)) {
            foreach ($offices as $key => $office) {
                if ($office['office_id'] == $office_info['office_id']) {
                    unset($offices[$key]);
                }
            }
        }

        $this->data['offices'] = $offices;

        if (isset($this->request->post['parent_id'])) {
            $this->data['parent_id'] = $this->request->post['parent_id'];
        } elseif (!empty($office_info)) {
            $this->data['parent_id'] = $office_info['parent_id'];
        } else {
            $this->data['parent_id'] = 0;
        }

        if (isset($this->request->post['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($office_info)) {
            $this->data['sort_order'] = $office_info['sort_order'];
        } else {
            $this->data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (!empty($office_info)) {
            $this->data['status'] = $office_info['status'];
        } else {
            $this->data['status'] = 1;
        }

        if (isset($this->request->post['phone'])) {
            $this->data['phone'] = $this->request->post['phone'];
        } elseif (!empty($office_info)) {
            $this->data['phone'] = $office_info['phone'];
        } else {
            $this->data['phone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $this->data['fax'] = $this->request->post['fax'];
        } elseif (!empty($office_info)) {
            $this->data['fax'] = $office_info['fax'];
        } else {
            $this->data['fax'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif (!empty($office_info)) {
            $this->data['email'] = $office_info['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['longitude'])) {
            $this->data['longitude'] = $this->request->post['longitude'];
        } elseif (!empty($office_info)) {
            $this->data['longitude'] = $office_info['longitude'];
        } else {
            $this->data['longitude'] = '';
        }

        if (isset($this->request->post['latitude'])) {
            $this->data['latitude'] = $this->request->post['latitude'];
        } elseif (!empty($office_info)) {
            $this->data['latitude'] = $office_info['latitude'];
        } else {
            $this->data['latitude'] = '';
        }

        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->template = 'common/offices_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'common/offices')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['office_description'] as $language_id => $value) {
            if ($value['name'] == '') {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ($value['address'] == '') {
                $this->error['address'][$language_id] = $this->language->get('error_address');
            }

            if ($value['title'] == '') {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if ($value['description'] == '') {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }
        }

        if ($this->request->post['longitude'] == '') {
            $this->error['longitude'] = $this->language->get('error_longitude');
        }

        if ($this->request->post['latitude'] == '') {
            $this->error['latitude'] = $this->language->get('error_latitude');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'common/offices')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>