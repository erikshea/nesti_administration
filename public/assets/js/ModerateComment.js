


$(() => {
    let commentActionLinks =  $(".edit__comments a");
    commentActionLinks.each( (i,linkNode) => {
        const parentContainer = linkNode.closest("div");
        const flagCell = linkNode.closest("tr").querySelector("td:nth-last-child(2)");

        $(linkNode).click( ()=> {
                $.post(vars.baseUrl + 'ajax/moderateComment', {
                    idRecipe: parentContainer.dataset.idrecipe,
                    idUsers: parentContainer.dataset.iduser,
                    blocks: linkNode.dataset.block,
                    csrf_token : vars.csrf_token
                }, (response) => {
                    if ( response.flag == "b" ) {
                        flagCell.innerHTML = "Bloqué";
                    } else if  ( response.flag == "a" ) {
                        flagCell.innerHTML = "Approuvé";
                    }
                })
            });
        })
    });
