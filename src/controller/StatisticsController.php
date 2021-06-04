<?php
class StatisticsController extends BaseController
{
    public function actionShow()
    {
        $this->setTemplateName("statistics/show");

        $connectionsByHour = [];
        for ($i = 0; $i < 24; $i++) {
            $countConnection = count(ConnectionLogDao::findAll(["HOUR(dateConnection)" => $i]));
            if ($countConnection) {
                $connectionsByHour[] =
                    [
                        "name" => $i + 1,
                        "data" => $countConnection
                    ];
            }
        }

        //to have sales and cost for order per days
        $startDate = new DateTime;
        $startDate->add(DateInterval::createFromDateString("-10 days"));

        $soldTotalByDay = [];
        $purchasedTotalByDay = [];

        for ($i = 9; $i >= 0; $i--) {
            $date = new DateTime;
            $date->add(DateInterval::createFromDateString("-{$i} days"));
            $day = intval($date->format('d'));
            $orders = OrdersDao::findAll(["dateCreation >" => $startDate->format('Y-m-d H:i:s'), "DAY(dateCreation)" => $day, "flag" => "a"]);
            $lots = LotDao::findAll(["dateReception >" => $startDate->format('Y-m-d H:i:s'), "DAY(dateReception)" => $day]);

            $soldTotal = 0;
            foreach ($orders as $order) {
                $soldTotal += $order->getTotal();
            }
            $soldTotalByDay[] = $soldTotal;

            $purchasedTotal = 0;
            foreach ($lots as $lot) {
                $purchasedTotal += $lot->getSubTotal();
            }

            $purchasedTotalByDay[] = $purchasedTotal;
        }



        $articleSales = [];
        $articlePurchases = [];
        foreach (ArticleDao::findAll(["a"]) as $article) {
            $articleSales[] = $article->getTotalSales();
            $articlePurchases[] = $article->getTotalPurchases();
        }

        // to have the top of users' connection
        $connectionsByIdUser = ConnectionLogDao::findAll(["INDEXBY" => "idUsers"]);
        //  sort by number of connections for each user id
        usort($connectionsByIdUser, function ($v1, $v2) {
            return count($v2) <=> count($v1);
        });

        //  get corresponding user for each group of connection logs
        $usersWithMostConnections = array_map(function ($v) {
            return $v[0]->getUser();
        }, $connectionsByIdUser);

        $usersWithMostConnections = array_slice($usersWithMostConnections, 0, 10);


        // to have the top of orders by sum
        $ordersByTotal = OrdersDao::findAll();
        usort($ordersByTotal, function ($o1, $o2) {
            return $o2->getTotal() <=> $o1->getTotal();
        });
        $ordersByTotal = array_slice($ordersByTotal, 0, 3);


        // to have the top of chef by recipe
        $chefsByRecipes = ChefDao::findAll();
        usort($chefsByRecipes, function ($o1, $o2) {
            return count($o2->getRecipes()) <=> count($o1->getRecipes());
        });
        $chefsByRecipes = array_slice($chefsByRecipes, 0, 10);


        // to have the top of recipe by grade
        $recipesByGrade =  RecipeDao::findAll();

        usort($recipesByGrade, function ($r1, $r2) {
            // average grade could be null if recipe has no grade, so use null coalescing operator with 0 if null
            return ($r2->getAverageGrade() ?? 0) <=> ($r1->getAverageGrade() ?? 0);
        });

        $recipesByGrade = array_slice($recipesByGrade, 0, 10);

        $articlesOutOfStock =  array_filter(ArticleDao::findAll(), function($a){ return $a->getStock() == 0; });
      
        $this->addVars([
            "usersWithMostConnections" => $usersWithMostConnections,
            "ordersByTotal" => $ordersByTotal,
            "chefsByRecipe" => $chefsByRecipes,
            "recipesByGrade" => $recipesByGrade,
            "articlesOutOfStock" => $articlesOutOfStock
        ]);

        $this->templateVars["javascriptVariables"] = [
            "connectionsByHour" => $connectionsByHour,
            "soldTotalByDay" => $soldTotalByDay,
            "purchasedTotalByDay" => $purchasedTotalByDay,
            "articleSales" => $articleSales,
            "articlePurchases" => $articlePurchases
        ];
        
        $this->templateVars['assets']['css'][] = [
            'href' => 'toastui-chart.min.css'
        ];

        $this->templateVars['assets']['js'][] = [
            'src' => 'toastui-chart.min.js'
        ];

        $this->templateVars['assets']['js'][] = [
            'src' => 'Statistics.js'
        ];
    }

    public function preRender(){
        parent::preRender();
    }

    public function dispatch($actionSlug,$options= [])
    {
        $this->setTemplateName('common/baseNoCrumbs', 'base');
        parent::dispatch($actionSlug);
    }
}
