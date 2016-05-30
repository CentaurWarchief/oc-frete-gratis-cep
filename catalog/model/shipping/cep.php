<?php

class ModelShippingCep extends Model
{
    const CODE = 'cep';

    /**
     * @param  string $cep
     * @return bool
     */
    private function isCoveredByFreeShipping($cep)
    {
        /* @var null|string[] $ceps */
        $ceps = $this->config->get('cep_ceps');

        if (! is_array($ceps)) {
            return false;
        }

        $cep = preg_replace('/[^\d]+/', '', $cep);

        return in_array($cep, $ceps, true);
    }

    /**
     * @param  string[] $address
     * @return array
     */
    public function getQuote(array $address)
    {
        $this->load->language('shipping/cep');

        if (! (bool) $this->config->get('cep_status')) {
            return [];
        }

        if (! $this->isCoveredByFreeShipping($address['postcode'])) {
            return [];
        }

        return [
            'code'  => self::CODE,
            'title' => $this->language->get('text_title'),
            'quote' => [
                self::CODE => [
                    'code'         => self::CODE . self::CODE,
                    'title'        => $this->language->get('text_title'),
                    'quote'        => 0,
                    'tax_class_id' => 0,
                    'text'         => $this->currency->format(0.00, $this->session->data['currency'])
                ]
            ],
            'sort_order' => $this->config->get('cep_sort_order'),
            'error'      => false
        ];
    }
}