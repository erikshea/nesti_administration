


$(() => {
    let commentActionLinks =  $(".edit__comments a");
    commentActionLinks.each( (i,linkNode) => {
        const parentContainer = linkNode.closest("div");
        const flagCell = linkNode.closest("tr").querySelector("td:nth-last-child(2)");

        $(linkNode).click( ()=> {
                $.post(vars.baseUrl + 'user/moderateCommentAjax', {
                    "idRecipe": parentContainer.dataset.idrecipe,
                    "idUsers": parentContainer.dataset.iduser,
                    "blocks": linkNode.dataset.block
                }, (response) => {
                    if ( response == "b" ) {
                        flagCell.innerHTML = "Bloqué";
                    } else if  ( response == "a" ) {
                        flagCell.innerHTML = "Approuvé";
                    }
                })
            });
        })
    });
