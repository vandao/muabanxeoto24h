<?php

use Phalcon\Mvc\User\Component;

class BuilderFilter extends Component
{
    public function filter($builder, $params = array(), $filters = array(), $sorts = array()) {
        if (!empty($params['typeSearch']) && !empty($params['keywordSearch'])){
            $params[$params['typeSearch']] = $params['keywordSearch'];
        }
        
        foreach ($filters as $type => $clauses) {
            foreach ($clauses as $key => $fieldName) {
                if (isset($params[$key])) {
                    switch ($key) {
                        case 'group_user':
                            $fieldName = User::getUserIdByGroup($fieldName);
                            break;
                    }

                    $value = $params[$key];
                    if (is_numeric($value) || $value != "" && ! is_array($value)) {
                        switch ($type) {
                            case 'likeAll':
                                $builder->andWhere("$fieldName LIKE :$key:", array($key => "%" . $value . "%"));
                                break;
                            case 'likeFirst':
                                $builder->andWhere("$fieldName LIKE :$key:", array($key => $value . "%"));
                                break;
                            default:
                                $builder->andWhere("$fieldName = :$key:", array($key => $value));
                                break;
                        }
                    }
                }
            }
        }

        foreach ($sorts as $key => $fieldName) {
            if (isset($params[$key])) {
                $value = $params[$key];
                
                $builder->orderBy("$fieldName $value");
            }
        }

        return $builder;
    }
}
