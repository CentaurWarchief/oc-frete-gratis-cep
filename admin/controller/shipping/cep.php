<?php

class ControllerShippingCep extends Controller
{
    /**
     * @var string
     */
    const EXTENSION = 'shipping/cep';

    public function index()
    {
        $this->language->load(self::EXTENSION);
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->persistSettingsAndRedirect();
        }

        $data = [];

        $this->injectVariablesFromLanguage($data, [
            'heading_title',
            'text_shipping',
            'text_edit',
            'text_disabled',
            'text_enabled',
            'text_success',
            'entry_status',
            'entry_sort_order',
            'entry_ceps',
            'help_ceps'
        ]);

        $data['breadcrumbs'] = $this->generateBreadcrumbsForToken($this->session->data['token']);
        $data['action']      = $this->url->link(self::EXTENSION, 'token=' . $this->session->data['token'], 'SSL');
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');


        if (! $this->loggedUserCanModifySettings()) {
            $data['error'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['ceps'])) {
            $data['cep_ceps'] = $this->prepareCepsFromText($this->request->post['ceps']);
        } else {
            $data['cep_ceps'] = $this->config->get('cep_ceps') ?: [];
        }

        $data['cep_status']     = $this->getValueFromPostOrConfig('cep_status');
        $data['cep_sort_order'] = $this->getValueFromPostOrConfig('cep_sort_order');

        $this->response->setOutput(
            $this->load->view(
                version_compare(VERSION, '2.2') < 0 ? 'shipping/cep.tpl' : 'shipping/cep',
                $data
            )
        );
    }

    /**
     * @param  string $cepsString
     * @return string[]
     */
    private function prepareCepsFromText($cepsString)
    {
        /* @var string[][] $matches */
        $matches = [];

        preg_match_all('/(?P<ceps>\b([\d]{8})\b)/', $cepsString, $matches);

        $ceps = isset($matches['ceps']) ? $matches['ceps'] : [];
        $ceps = array_unique($ceps);

        return $ceps;
    }

    private function persistSettingsAndRedirect()
    {
        if (! $this->loggedUserCanModifySettings()) {
            return;
        }

        $cepsString = $this->request->post['ceps'];

        unset($this->request->post['ceps']);

        $this->model_setting_setting->editSetting(
            'cep',
            array_merge($this->request->post, [
                'cep_ceps' => $this->prepareCepsFromText($cepsString)
            ])
        );

        $this->session->data['success'] = $this->language->get('text_success');

        $this->response->redirect(
            $this->url->link(
                'extension/shipping',
                'token=' . $this->session->data['token'],
                'SSL'
            )
        );
    }

    /**
     * @param  string $name
     * @return string
     */
    private function getValueFromPostOrConfig($name)
    {
        if (isset($this->request->post[$name])) {
            return $this->request->post[$name];
        }

        return $this->config->get($name);
    }

    /**
     * @param array $data
     * @param array $variables
     */
    private function injectVariablesFromLanguage(array & $data, array $variables)
    {
        foreach ($variables as $variable) {
            $data[$variable] = $this->language->get($variable);
        }
    }

    /**
     * @param  string $token
     * @return array
     */
    private function generateBreadcrumbsForToken($token)
    {
        return [
            [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $token, 'SSL')
            ],
            [
                'text' => $this->language->get('text_shipping'),
                'href' => $this->url->link('extension/shipping', 'token=' . $token, 'SSL')
            ],
            [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link(self::EXTENSION, 'token=' . $token, 'SSL')
            ]
        ];
    }

    /**
     * @return bool
     */
    private function loggedUserCanModifySettings()
    {
        return $this->user->hasPermission(
            'modify',
            self::EXTENSION
        );
    }
}