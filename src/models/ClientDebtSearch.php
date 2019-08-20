<?php
/**
 * ClientDebt module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-client-debt
 * @package   hipanel-client-debt
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\client\debt\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;


class ClientDebtSearch extends ClientDebt
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'created_from', 'created_till',
            'types', 'states', 'debt_gt', 'debt_lt', 'debt_depth_gt', 'debt_depth_lt', 'debt', 'login_email_like',
        ]);
    }

}
