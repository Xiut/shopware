<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */
class Shopware_Controllers_Api_PaymentMethods extends Shopware_Controllers_Api_Rest
{
    /**
     * @var Shopware\Components\Api\Resource\PaymentMethods
     */
    protected $resource = null;

    public function init()
    {
        $this->resource = \Shopware\Components\Api\Manager::getResource('PaymentMethods');
    }

    /**
     * Returns the current version
     */
    public function indexAction()
    {
        $limit = (int) $this->Request()->getParam('limit', 1000);
        $offset = (int) $this->Request()->getParam('start', 0);
        $sort = $this->Request()->getParam('sort', []);
        $filter = $this->Request()->getParam('filter', []);

        $result = $this->resource->getList($offset, $limit, $filter, $sort);

        $this->View()->assign($result);
        $this->View()->assign('success', true);
    }

    /**
     * Get one payment
     *
     * GET /api/payment/{id}
     */
    public function getAction()
    {
        $id = $this->Request()->getParam('id');

        $media = $this->resource->getOne($id);

        $this->View()->assign('data', $media);
        $this->View()->assign('success', true);
    }

    /**
     * Create new payment
     *
     * POST /api/payment
     */
    public function postAction()
    {
        $params = $this->Request()->getPost();

        $payment = $this->resource->create($params);

        $location = $this->apiBaseUrl . 'paymentMethods/' . $payment->getId();
        $data = [
            'id' => $payment->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Update payment
     *
     * PUT /api/payment/{id}
     */
    public function putAction()
    {
        $id = $this->Request()->getParam('id');
        $params = $this->Request()->getPost();

        $payment = $this->resource->update($id, $params);

        $location = $this->apiBaseUrl . 'paymentMethods/' . $payment->getId();
        $data = [
            'id' => $payment->getId(),
            'location' => $location,
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
    }

    /**
     * Delete payment
     *
     * DELETE /api/payment/{id}
     */
    public function deleteAction()
    {
        $id = $this->Request()->getParam('id');

        $this->resource->delete($id);

        $this->View()->assign(['success' => true]);
    }
}
