<?php

class RecipeController extends EntityController
{
    public function actionAdd(){
        $this->forward('edit');
    }

    public function actionUpdateParagraphsAjax(){
        $result = []; // need to return result array with updated ids (if paragraph inserted)
        foreach ($_POST["paragraphs"] as $index=>$paragraphArray ){
            if ( $paragraphArray['idParagraph'] == null ){
                $paragraph = new Paragraph;
                $paragraph->setIdRecipe($this->getEntity()->getId());
            } else {
                $paragraph = ParagraphDao::findById($paragraphArray['idParagraph']);
            }

            if ( $paragraphArray["toDelete"] ?? false ){
                ParagraphDao::delete($paragraph);
            } else {
                $paragraph->setContent($paragraphArray["content"]);
                $paragraph->setParagraphPosition($index+1);
    
                ParagraphDao::saveOrUpdate($paragraph);
                $result[] = EntityUtil::toArray($paragraph);
            }
        }

        echo json_encode($result);
    }

    public function actionGetParagraphsAjax(){
        $paragraphs = $this->getEntity()->getParagraphs(["ORDER"=>"paragraphPosition ASC"]);
        
        echo json_encode(EntityUtil::toArray($paragraphs));
    }


    public function actionEdit()
    {
        $imageUrl = null;
        if ( $this->getEntity() != null && $this->getEntity()->getImage() != null ){
            $imageUrl = SiteUtil::url(
                "public/assets/images/content/" . $this->getEntity()->getImage()->getFileName()
            );
        }

        $this->addVars([ "imageUrl" => $imageUrl ]);
        
        $this->templateVars['assets']['js'][] = [
            'src'=>"ParagraphList.js",
            "type" => "text/babel",
            "addLast" => true
        ];

        parent::actionEdit();
    }

    public function preRender()
    {
        parent::preRender();
        $this->templateVars['assets']['css'][] = "recipe.css";
        $this->templateVars['assets']['css'][] = "image-upload.css";
        $this->templateVars['assets']['js'][] = [
            'src' => 'react.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'react-dom.development.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src' => 'babel.min.js'
        ];
        $this->templateVars['assets']['js'][] = [
            'src'=>"recipe.js",
            "type" => "text/babel"
        ];
    }
}
