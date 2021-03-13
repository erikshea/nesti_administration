


class OrderItems extends React.Component {
    state = { orderItems: [], id: null};

    showOrder(id) {
        $.post(vars.baseUrl + 'article/getOrderItemsAjax', { "idOrders": id }, (response) => {
            this.setState(JSON.parse(response));
        });
    }


    render() {
        const items = this.state.orderItems.map((oi, index) => {
            return <OrderItem key={index} {...oi}/>;
        });


        return this.state.id && ( // Hide if no id set
            <div>
                <div className="d-flex justify-content-between">
                    <h2>Détail</h2>
                    <h4 className="color-secondary-dark">N°{this.state.id}</h4>
                </div>
                <div className="order-items__list primary-border flex-column h-100 p-3">
                    {items}
                </div>
            </div>
        );
    }
}

const OrderItem = (props)=>{
    return (
        <div className="d-flex justify-content-between">
            <div>
                <span>{props.quantity} {props.unitName} : </span>
                <strong>{props.articleName}</strong>
            </div>
            <a href={vars.baseUrl + "article/edit/" + props.idArticle}>Voir</a>
        </div>
    );
}

$( ()=>{
    let orderItemsDiv = document.getElementById('order-items');
    if ( orderItemsDiv ){
        let orderItems = ReactDOM.render(<OrderItems />, orderItemsDiv);
        
        $(".orders__table tbody tr").click( (e) => {
            let row = e.target.parentNode;
            $(".orders__table tbody tr").removeClass("bg-color-secondary");
            row.className += "bg-color-secondary";
            let id = row.querySelector("td:first-child").innerText;
            orderItems.showOrder(id);
        })
    }
})
