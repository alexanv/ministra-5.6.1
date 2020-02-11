<?php

namespace Ministra\Admin\Controller;

use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9;
use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\ba724b2e05050449eb89f699644015af;
use Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\Validator\Constraints as Assert;
class CertificatesController extends \Ministra\Admin\Controller\BaseMinistraController
{
    protected $db;
    private $licsServerErrors;
    public function __construct(\Silex\Application $app)
    {
        parent::__construct($app, __CLASS__);
        $this->licsServerErrors = [];
        $this->app['allLicCount'] = $this->getLicenseCountAndCost();
        $this->app['allStatus'] = [['id' => 1, 'title' => $this->setLocalization('Valid'), 'label' => 'ok'], ['id' => 2, 'title' => $this->setLocalization('Requested'), 'label' => 'not_valid'], ['id' => 3, 'title' => $this->setLocalization('Blocked'), 'label' => 'disabled'], ['id' => 4, 'title' => $this->setLocalization('expired'), 'label' => 'expired'], ['id' => 5, 'title' => $this->setLocalization('Wrong signature'), 'label' => 'wrong_signature'], ['id' => 6, 'title' => $this->setLocalization('Undefined'), 'label' => 'undefined']];
    }
    private function getLicenseCountAndCost()
    {
        $return = [];
        try {
            $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
            $lics = $sert->ce3fde578c12c85b59dd5d42126c4307();
            while (list($count, $cost) = \each($lics)) {
                $return[$count] = ['id' => $count, 'title' => \number_format($count, 0, '.', ' '), 'count' => $count, 'cost' => $cost];
            }
        } catch (\Ministra\Lib\S642b6461e59cef199375bfb377c17a39\ba724b2e05050449eb89f699644015af $e) {
            $date = new \DateTime();
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
            \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
        } catch (\Exception $e) {
            $date = new \DateTime();
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
            \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
        }
        return $return;
    }
    public function index()
    {
        if (empty($this->app['action_alias'])) {
            return $this->app->redirect($this->app['controller_alias'] . '/current');
        }
        $this->app['licsServerErrors'] = $this->licsServerErrors;
        return $this->app['twig']->render($this->getTemplateName(__METHOD__));
    }
    public function current()
    {
        if (empty($this->data['filters'])) {
            $this->data['filters'] = [];
        }
        $this->app['filters'] = $this->data['filters'];
        $attribute = $this->getDropdownAttribute();
        $this->checkDropdownAttribute($attribute);
        $this->app['dropdownAttribute'] = $attribute;
        $data_set = $this->current_list_json(true);
        $this->app['data_set'] = $data_set['data'];
        $this->app['lic_count_set'] = \array_combine($this->getFieldFromArray($this->app['allLicCount'], 'count'), $this->getFieldFromArray($this->app['allLicCount'], 'title'));
        $this->app['status_set'] = \array_combine($this->getFieldFromArray($this->app['allStatus'], 'id'), $this->getFieldFromArray($this->app['allStatus'], 'title'));
        $this->app['licsServerErrors'] = $this->licsServerErrors;
        return $this->app['twig']->render($this->getTemplateName(__METHOD__));
    }
    private function getDropdownAttribute()
    {
        $attribute = [['name' => 'id', 'title' => $this->setLocalization('ID'), 'checked' => true], ['name' => 'lic_count', 'title' => $this->setLocalization('License count'), 'checked' => true], ['name' => 'cert_begin', 'title' => $this->setLocalization('Begin of certificate validity'), 'checked' => true], ['name' => 'cert_end', 'title' => $this->setLocalization('End of certificate validity'), 'checked' => true], ['name' => 'status', 'title' => $this->setLocalization('Status'), 'checked' => true]];
        return $attribute;
    }
    public function current_list_json($local_use = false)
    {
        if (!$this->isAjax && !$local_use) {
            $this->app->abort(404, $this->setLocalization('Page not found'));
        }
        $data = [];
        if (!empty($this->postData['notty_check'])) {
            $data['action'] = 'topModalMsg';
        }
        $param = !empty($this->data) ? $this->data : $this->postData;
        $data['data'] = [];
        if (!empty($param['id'])) {
            $data['id'] = $param['id'];
        }
        $error = '';
        try {
            $status_label = \array_combine($this->getFieldFromArray($this->app['allStatus'], 'label'), $this->getFieldFromArray($this->app['allStatus'], 'id'));
            $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
            if ($local_use && !empty($param['id'])) {
                $lics_arr = [$sert->getLicense($param['id'])];
            } else {
                $lics_arr = $sert->getLicenses();
            }
            $expires_30_days = 60 * 60 * 24 * 30;
            $now = \time();
            while (list($num, $lics) = \each($lics_arr)) {
                $error .= $lics->getError();
                $data['data'][] = ['id' => $lics->getId(), 'lic_count' => $lics->t6edfbd023c0528d4c6b5362f7d3773bc(), 'cert_begin' => $lics->Y8a8e4333c517704f730876ab1b4fb03f(), 'cert_end' => $lics->df439691e7f7521b38565296ca6671c8(), 'status' => $status_label[$lics->X09ddd22400b69d4d3bf474b296e11159()], 'status_bool' => $lics->getStatus(), 'awaiting' => $lics->getStatus() && $lics->getHash() !== $lics->H3e24e67afe8453a5eeacb5c84ec3be0e(), 'expires_30_days' => $lics->df439691e7f7521b38565296ca6671c8() - $now <= $expires_30_days, 'RowOrder' => 'dTRow_' . $lics->getId()];
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $response = $this->generateAjaxResponse($data, $error);
        return $local_use ? $response : new \Symfony\Component\HttpFoundation\Response(\json_encode($response), empty($error) ? 200 : 500);
    }
    public function certificate_request()
    {
        $data = [];
        if (!empty($this->data['id'])) {
            try {
                $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
                $lics = $sert->getLicense($this->data['id']);
                $now = \time();
                $expires = \floor(($lics->df439691e7f7521b38565296ca6671c8() - $now) / (60 * 60 * 24));
                $data = ['id' => $lics->getId(), 'company' => $lics->R2b91dcfd40a2ceae6e82a42dae170e11(), 'contact_name' => $lics->n9b9e7cc19c57249198dc7d687810fe94(), 'phone' => $lics->cef17915a17286d45139e88900e6722e(), 'contact_address' => $lics->f11c845c1953ccda538cf63855fd9965(), 'country' => $lics->b22ca90f577ec1ca7976613d81056b01(), 'quantity' => $lics->t6edfbd023c0528d4c6b5362f7d3773bc(), 'date_begin' => $lics->Y8a8e4333c517704f730876ab1b4fb03f(), 'date_to' => $lics->df439691e7f7521b38565296ca6671c8(), 'period' => (int) \date('Y', $lics->df439691e7f7521b38565296ca6671c8()) - (int) \date('Y', $lics->Y8a8e4333c517704f730876ab1b4fb03f()), 'expire' => $expires, 'status' => $lics->X09ddd22400b69d4d3bf474b296e11159(), 'is_show' => false];
            } catch (\Ministra\Lib\S642b6461e59cef199375bfb377c17a39\ba724b2e05050449eb89f699644015af $e) {
                $date = new \DateTime();
                \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
                \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
            } catch (\Exception $e) {
                $date = new \DateTime();
                \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
                \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
            }
        } elseif (!empty($this->postData)) {
            $data = $this->postData;
        }
        $form = $this->buildCertificateRequestForm($data, !empty($data['form']['is_show']));
        if ($this->saveCertificateRequestData($form)) {
            return $this->app->redirect('current');
        }
        $this->app['form'] = $form->createView();
        $this->app['certificateRequestEdit'] = false;
        $allLicenseCountAndCost = \array_combine($this->getFieldFromArray($this->app['allLicCount'], 'count'), \array_values($this->app['allLicCount']));
        while (list($id, $row) = \each($allLicenseCountAndCost)) {
            $allLicenseCountAndCost[$id]['title'] .= ' ' . $this->setLocalization($id == 1 ? 'device' : 'devices');
        }
        $this->app['allLicenseCountAndCost'] = $allLicenseCountAndCost;
        $this->app['breadcrumbs']->addItem($this->setLocalization('List of certificates'), $this->app['controller_alias'] . '/current');
        $this->app['breadcrumbs']->addItem($this->setLocalization('Certificate request'));
        $this->app['licsServerErrors'] = $this->licsServerErrors;
        return $this->app['twig']->render($this->getTemplateName(__METHOD__));
    }
    private function buildCertificateRequestForm(&$data = array(), $show = false)
    {
        $this->app['is_show'] = $show;
        $builder = $this->app['form.factory'];
        $quantity = $this->app['allLicCount'];
        while (list($id, $row) = \each($quantity)) {
            $quantity[$id]['title'] .= ' ' . $this->setLocalization($id == 1 ? 'device' : 'devices');
        }
        $countries_name = $this->app['language'] == 'ru' ? 'name' : 'name_en';
        $countries = $this->db->getAllFromTable('countries', $countries_name);
        $countries = \array_combine($this->getFieldFromArray($countries, 'iso2'), $this->getFieldFromArray($countries, $countries_name));
        $form = $builder->createBuilder('form', $data)->add('id', $show ? 'text' : 'hidden')->add('company', 'text', ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank()], 'attr' => ['readonly' => $show, 'disabled' => $show], 'required' => true])->add('contact_name', 'text', ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank(), new \Symfony\Component\Validator\Constraints\Regex(['pattern' => '/^[^\\d]+$/'])], 'attr' => ['readonly' => $show, 'disabled' => $show], 'required' => true])->add('phone', 'text', ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank(), new \Symfony\Component\Validator\Constraints\Regex(['pattern' => '/^[\\d\\+\\-]+$/'])], 'attr' => ['readonly' => $show, 'disabled' => $show], 'required' => true])->add('contact_address', 'text', ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank()], 'attr' => ['readonly' => $show, 'disabled' => $show], 'required' => true])->add('country', 'choice', ['choices' => ['' => ''] + $countries, 'required' => true, 'attr' => ['readonly' => $show, 'disabled' => $show], 'data' => empty($data['country']) ? '' : $data['country']])->add('quantity', 'choice', ['choices' => ['' => ''] + \array_combine($this->getFieldFromArray($quantity, 'count'), $this->getFieldFromArray($quantity, 'title')), 'required' => true, 'attr' => ['readonly' => $show, 'disabled' => $show], 'data' => empty($data['quantity']) ? 0 : $data['quantity']])->add('server_host', 'text', ['constraints' => [new \Symfony\Component\Validator\Constraints\NotBlank()], 'attr' => ['readonly' => $show, 'disabled' => $show], 'required' => true])->add('save', 'submit');
        $status_ids = ['' => ''] + \array_combine($this->getFieldFromArray($this->app['allStatus'], 'label'), $this->getFieldFromArray($this->app['allStatus'], 'title'));
        $form->add('date_to', $show ? 'date' : 'hidden', $show ? ['attr' => ['readonly' => $show, 'disabled' => $show], 'input' => 'timestamp', 'widget' => 'single_text', 'format' => 'dd.MM.yyyy', 'empty_value' => \time()] : [])->add('date_begin', $show ? 'date' : 'hidden', $show ? ['attr' => ['readonly' => $show, 'disabled' => $show], 'input' => 'timestamp', 'widget' => 'single_text', 'format' => 'dd.MM.yyyy', 'empty_value' => \time(), 'required' => false] : [])->add('status', $show ? 'choice' : 'hidden', $show ? ['choices' => $status_ids, 'attr' => ['disabled' => $show]] : [])->add('expire', 'hidden');
        return $form->getForm();
    }
    private function saveCertificateRequestData(&$form)
    {
        if (!empty($this->method) && $this->method == 'POST') {
            $form->handleRequest($this->request);
            $data = $form->getData();
            if ($form->isValid()) {
                $date = new \DateTime('now');
                $data['date_from'] = $date->getTimestamp();
                if ($data['quantity'] == 1) {
                    $date->modify('+3 month');
                } else {
                    $date->modify('+1 year');
                }
                $data['date_to'] = $date->getTimestamp();
                $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
                try {
                    return $sert->C5f9c13ecdbb38b4c5570d72d7ecb18ee((string) $data['contact_name'], (string) $data['contact_address'], (int) $data['quantity'], (int) $data['date_from'], (int) $data['date_to'], (string) $data['server_host'], (string) $data['country'], (string) $data['company'], (string) $data['phone']);
                } catch (\Ministra\Lib\S642b6461e59cef199375bfb377c17a39\ba724b2e05050449eb89f699644015af $e) {
                    $form->addError(new \Symfony\Component\Form\FormError($e->getMessage()));
                    return false;
                }
            }
        }
        return false;
    }
    public function certificate_detail()
    {
        $id = !empty($this->data['id']) ? (int) $this->data['id'] : false;
        if (!$id) {
            return $this->app->redirect('current');
        }
        try {
            $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
            $lics = $sert->getLicense($id);
            $now = \time();
            $expires = \floor(($lics->df439691e7f7521b38565296ca6671c8() - $now) / (60 * 60 * 24));
            $data = ['id' => $lics->getId(), 'company' => $lics->R2b91dcfd40a2ceae6e82a42dae170e11(), 'contact_name' => $lics->n9b9e7cc19c57249198dc7d687810fe94(), 'phone' => $lics->cef17915a17286d45139e88900e6722e(), 'contact_address' => $lics->f11c845c1953ccda538cf63855fd9965(), 'country' => $lics->b22ca90f577ec1ca7976613d81056b01(), 'quantity' => $lics->t6edfbd023c0528d4c6b5362f7d3773bc(), 'date_begin' => $lics->Y8a8e4333c517704f730876ab1b4fb03f(), 'date_to' => $lics->df439691e7f7521b38565296ca6671c8(), 'period' => (int) \date('Y', $lics->df439691e7f7521b38565296ca6671c8()) - (int) \date('Y', $lics->Y8a8e4333c517704f730876ab1b4fb03f()), 'expire' => $expires, 'status' => $lics->X09ddd22400b69d4d3bf474b296e11159(), 'is_show' => true];
            $form = $this->buildCertificateRequestForm($data, true);
            if ($expires >= 0 && $expires <= 30) {
                $form->get('date_to')->addError(new \Symfony\Component\Form\FormError($this->setLocalization('Validity of the certificate expires after {expire} days', '', $expires, ['{expire}' => $expires])));
            } elseif ($expires < 0) {
                $form->get('date_to')->addError(new \Symfony\Component\Form\FormError($this->setLocalization('Validity of the certificate has expired {expire} days ago', '', \abs($expires), ['{expire}' => \abs($expires)])));
            }
            if ($data['status'] == 'wrong_signature') {
                $form->get('status')->addError(new \Symfony\Component\Form\FormError($this->setLocalization('The certificate does not match the server configuration')));
            }
        } catch (\Ministra\Lib\S642b6461e59cef199375bfb377c17a39\ba724b2e05050449eb89f699644015af $e) {
            $data = [];
            $form = $this->buildCertificateRequestForm($data, true);
            $date = new \DateTime();
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
            \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
        } catch (\Exception $e) {
            $data = [];
            $form = $this->buildCertificateRequestForm($data, true);
            $date = new \DateTime();
            \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\Z860a165ed018f157fd40ef2297209b46\s11f4c3e4ac7fcef8584efe64e972b115::q6ee195c1759171b9aef09286fb44db47($date->format('Y-m-d H:i:s') . ' - LicenseManager error ' . $e->getMessage() . ' on ' . __FILE__ . ' line ' . __LINE__ . PHP_EOL);
            \array_push($this->licsServerErrors, $this->setLocalization('No connection to the server'));
        }
        $this->app['form'] = $form->createView();
        $allLicenseCountAndCost = \array_combine($this->getFieldFromArray($this->app['allLicCount'], 'count'), \array_values($this->app['allLicCount']));
        while (list($id, $row) = \each($allLicenseCountAndCost)) {
            $allLicenseCountAndCost[$id]['title'] .= ' ' . $this->setLocalization($id == 1 ? 'device' : 'devices');
        }
        $this->app['allLicenseCountAndCost'] = $allLicenseCountAndCost;
        $this->app['breadcrumbs']->addItem($this->setLocalization('List of certificates'), $this->app['controller_alias'] . '/current');
        $this->app['breadcrumbs']->addItem($this->setLocalization('Certificate detail'));
        $this->app['licsServerErrors'] = $this->licsServerErrors;
        return $this->app['twig']->render($this->getTemplateName('Certificates::certificate_request'));
    }
    public function certificate_install()
    {
        if (!$this->isAjax || $this->method != 'POST' || empty($this->postData['id'])) {
            $this->app->abort(404, $this->setLocalization('Page not found'));
        }
        $data = [];
        $data['action'] = 'updateTableData';
        $error = '';
        try {
            $sert = new \Ministra\Lib\S642b6461e59cef199375bfb377c17a39\v6e07498b186de24a658aff1884c2d2f9();
            $sert->d4a6341a79614469be5d02681140c8873((int) $this->postData['id']);
            $data = \array_merge_recursive($data, $this->current_list_json(true));
            $data['action'] = 'updateTableRow';
            $data['msg'] = $this->setLocalization('Installed');
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $response = $this->generateAjaxResponse($data, $error);
        return new \Symfony\Component\HttpFoundation\Response(\json_encode($response), empty($error) ? 200 : 500);
    }
}
