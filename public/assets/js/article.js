


class OrderItems extends React.Component {
    state = { orderItems: []};

    showOrder(id) {
        $.post(vars.baseUrl + 'article/getOrderItemsAjax', { "idOrders": id }, (response) => {
            this.setState(JSON.parse(response));
        });
    }


    render() {
        const items = this.state.orderItems.map((oi, index) => {
            return <OrderItem key={index} {...oi}/>;
        });


        return (
            <div className="order-items__list primary-border flex-column h-100 p-3">
                {items}
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
        
        $(".article__orders tbody tr").click( (e) => {
            let id = e.target.parentNode.querySelector("td:first-child").innerText;
            orderItems.showOrder(id);
        })
    }
})
