<?php

class ControllerPaymentPagarMeCartao extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('payment/pagar_me_cartao');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('pagar_me_cartao', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_edit'] = $this->language->get('text_edit');

        $admin_options_texts = array(
            'criptografia',
            'api',
            'nome',
            'max_parcelas',
            'parcelas_sem_juros',
            'valor_parcela',
            'taxa_juros',
            'text_information',
            'order_status',
            'order_processing',
            'order_paid',
            'order_refused',
            'order_refunded',
            'geo_zone',
            'async',
            'status',
            'sort_order',
            'total'
        );

        foreach($admin_options_texts as $text){
            $data['entry_'.$text] = $this->language->get('entry_'.$text);
        }

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $help_texts = array (
            'criptografia',
            'api',
            'nome',
            'max_parcelas',
            'parcelas_sem_juros',
            'taxa_juros',
            'async'
        );

        foreach($help_texts as $text){
            $data['help_'.$text] = $this->language->get('help_'.$text);
        }

        $error_fields = array(
            'warning',
            'criptografia',
            'max_parcelas',
            'parcelas_sem_juros',
            'valor_parcelas',
            'taxa_juros',
            'api',
            'nome'
        );

        foreach($error_fields as $error){
            $data['error_'.$error] = '';
            if(isset($this->error[$error])){
                $data['error_'.$error] = $this->error[$error];
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('payment/pagar_me_cartao', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('payment/pagar_me_cartao', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $admin_configuration_options = array(
            'pagar_me_cartao_nome',
            'pagar_me_cartao_criptografia',
            'pagar_me_cartao_api',
            'pagar_me_cartao_text_information',
            'pagar_me_cartao_max_parcelas',
            'pagar_me_cartao_taxa_juros',
            'pagar_me_cartao_parcelas_sem_juros',
            'pagar_me_cartao_valor_parcela',
            'pagar_me_cartao_order_processing',
            'pagar_me_cartao_order_paid',
            'pagar_me_cartao_order_refused',
            'pagar_me_cartao_order_refunded',
            'pagar_me_cartao_async',
            'pagar_me_cartao_geozone_id',
            'pagar_me_cartao_status',
            'pagar_me_cartao_sort_order'
        );

        foreach($admin_configuration_options as $option){
            $data[$option] = $this->config->get($option);
            if(isset($this->request->post[$option])){
                $data[$option] = $this->request->post[$option];
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/pagar_me_cartao.tpl', $data));
    }

    private function validate() {

        if (!$this->user->hasPermission('modify', 'payment/pagar_me_cartao')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['pagar_me_cartao_criptografia']) {
            $this->error['criptografia'] = $this->language->get('error_criptografia');
        }

        if (!$this->request->post['pagar_me_cartao_taxa_juros']) {
            $this->error['taxa_juros'] = $this->language->get('error_taxa_juros');
        }

        if (!$this->request->post['pagar_me_cartao_max_parcelas']) {
            $this->error['max_parcelas'] = $this->language->get('error_max_parcelas');
        }

        if (!$this->request->post['pagar_me_cartao_parcelas_sem_juros']) {
            $this->error['parcelas_sem_juros'] = $this->language->get('error_parcelas_sem_juros');
        }

        if (!$this->request->post['pagar_me_cartao_valor_parcela']) {
            $this->error['valor_parcela'] = $this->language->get('error_valor_parcela');
        }

        if (!$this->request->post['pagar_me_cartao_api']) {
            $this->error['api'] = $this->language->get('error_api');
        }

        if (!$this->request->post['pagar_me_cartao_nome']) {
            $this->error['nome'] = $this->language->get('error_nome');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function install() {
        $this->load->model('payment/pagar_me_cartao');
        $this->model_payment_pagar_me_cartao->install();
    }

    public function uninstall() {
        $this->load->model('payment/pagar_me_cartao');
        $this->model_payment_pagar_me_cartao->uninstall();
    }

}

?>
