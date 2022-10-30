<?php

declare (strict_types = 1);

namespace App\Controllers;

class WebController
{
    /**
     * @param $app
     */
    public function queryUsersDashboard($app, $filter = true)
    {
        $bindings = [
            'fromDate' => $app->request()->input['fromDate'],
            'toDate' => $app->request()->input['toDate']
        ];

        $statement = [
            'SELECT user.*, comments.comment,',
            '(SELECT COUNT(orders.id) FROM orders where user.id=orders.user_id) AS orders,',
            '(SELECT SUM(orders.price) FROM orders where user.id=orders.user_id) AS price,',
            '(SELECT MAX(orders.data_create) FROM orders where user.id=orders.user_id) AS order_latest',
            'FROM user LEFT JOIN comments ON user.id=comments.user_id'
        ];
        // where
        if ($filter && array_filter($bindings)) {
            array_push($statement, 'WHERE user.id IN (SELECT orders.user_id FROM orders WHERE orders.data_create BETWEEN :fromDate AND :toDate)');
        }
        // order
        array_push($statement, 'ORDER BY user.id');
        // execute
        return $app->dbm()->fetchAll(implode(' ', $statement), $bindings);
    }

    /**
     * @param $app
     */
    public function getIndex($app)
    {
        $app->redirectTo($app->getDefaultPath());
    }

    /**
     * @param $app
     */
    public function getDashboard($app, $me)
    {
        $items = $me->queryUsersDashboard($app, false);

        $app->loadView($app->viewPath(__FUNCTION__), compact('items'));
    }

    /**
     * @param $app
     */
    public function postDashboard($app, $me)
    {
        $items = $me->queryUsersDashboard($app);

        $submit = $app->request()->input['submit'];

        $fileName = date("YmdHis") . '-' . ($app->request()->input['downloadName'] ?: 'export-data.table');

        if (stripos($submit, 'Download') !== false) {
            $app->downloader($items)->setFileName($fileName)->setFileType(substr($submit, 0, -8))->download();
        }

        $app->loadView($app->viewPath(__FUNCTION__), compact('items'));
    }

    /**
     * @param $app
     */
    public function getPalindrome($app)
    {
        $items = [];

        $app->loadView($app->viewPath(__FUNCTION__), compact('items'));
    }

    /**
     * @param $app
     */
    public function postPalindrome($app)
    {
        $items = [];

        foreach (array_filter($app->request()->input, 'is_array') as $type => $vals) {
            foreach ($vals as $key => $val) {
                $items[$val] = $app->isPalindrome($val);
            }
        }

        $app->loadView($app->viewPath(__FUNCTION__), compact('items'));
    }

    /**
     * @param $app
     */
    public function getFiles($app)
    {
        $items = [];
        $group = [];

        foreach ($app->listSessionFiles() as $item) {
            $items[$item] = $app->extractStartEndDate($item);
            // when was the max number of active sessions
            $day = strstr($items[$item]['startDateString'], ' ', true);
            // init group
            array_key_exists($day, $group) ?: $group[$day] = [];
            // add item
            $group[$day][] = $items[$item];
        }
        // count by group by max active session
        $group = array_map(function ($items) {return count($items);}, $group);
        // asc
        ksort($group);
        // display only first max
        $group = array_unique($group);

        $app->loadView($app->viewPath(__FUNCTION__), compact('items', 'group'));
    }

    /**
     * @param $app
     */
    public function postFiles($app)
    {
        $items = [];

        $todo = $app->dd($app->request()->all());

        $app->loadView($app->viewPath(__FUNCTION__), compact('items'));
    }
}
