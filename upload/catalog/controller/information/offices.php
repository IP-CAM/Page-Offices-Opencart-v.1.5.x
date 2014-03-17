<?php
class ControllerInformationOffices extends Controller {
    private $error = array();

    public function index() {
        $this->language->load('information/offices');
        $this->load->model('module/offices');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('information/offices'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_address'] = $this->language->get('text_address');
        $this->data['text_email'] = $this->language->get('text_email');
        $this->data['text_telephone'] = $this->language->get('text_telephone');
        $this->data['text_fax'] = $this->language->get('text_fax');
        $this->data['text_email'] = $this->language->get('text_email');
        $this->data['text_traffic'] = $this->language->get('text_traffic');
        $this->data['text_show_traffic'] = $this->language->get('text_show_traffic');
        $this->data['language_code'] = $this->language->get('code');

        $offices = $this->model_module_offices->getOffices();

        $this->data['offices'] = array();
        foreach ($offices AS $office) {
            $office['parent'] = ($office['parent_id'] != 0) ? ' parent' : '';
            $office['description'] = preg_replace("#\\\r\\\n#", "<br>", $office['description']);
            $this->data['offices'][] = $office;
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/offices.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/offices.tpl';
        } else {
            $this->template = 'default/template/information/offices.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }
}
?>