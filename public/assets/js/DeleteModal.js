const DeleteModal = (props) => {
    // need a reference to modal node for show and hide
    const modal = React.useRef(null);

    // on component mount, show modal
    React.useEffect( () => $(modal.current).modal('show') );

    const clickFunction = () => {
        if ( props.confirm instanceof Function ) {
            props.confirm(); // If confirm property is a function, execute it
        } else { 
            window.location.href = vars.baseUrl + props.confirm; // else, assume it's a relative url 
        }
        $(modal.current).modal('hide');
    };

    return (
        <div ref={modal} className="modal" tabIndex="-1" role="dialog">
            <div className="modal-dialog" role="document">
                <div className="modal-content">
                <div className="modal-header ">
                    <h6>Suppression</h6>
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div className="modal-body d-flex flex-column align-items-center justify-content-around">
                    <h5 className="modal-title">
                        <i className="fas fa-exclamation-triangle color-danger"></i>
                        Voulez-vous vraiment supprimer l'élément:
                    </h5>
                    <strong className="element-name">{props.elementName}</strong>
                    <p className="mt-4">Cette action est irréversible.</p>
                </div>
                <div className="modal-footer">
                    <a type="button" onClick={clickFunction} className="confirm-button btn btn-success">Confirmer</a>
                    <button type="button" className="btn btn-danger" data-dismiss="modal">Annuler</button>
                </div>
                </div>
            </div>
        </div>
    );
}