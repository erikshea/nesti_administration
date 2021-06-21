
class OrderItems extends React.Component {
    state = { orderItems: [], id: null};
    showOrder(id) {
        $.post(
            vars.baseUrl + 'ajax/getOrderItems',
            {
                idOrders: id,
                csrf_token : vars.csrf_token
            },
            response => this.setState(response)
        );
    }
    render() {
        const items =
            this.state.orderItems.map( (oi, index) => <OrderItem key={index} {...oi}/> );

        return this.state.id && ( // Hide if no id set
            <div>
                <div className="d-flex justify-content-between">
                    <h2>Détail</h2>
                    <h4 className="color-secondary-dark">N°{this.state.id}</h4>
                </div>
                <div className="order-items__list primary-border flex-column h-100 p-3">
                    {items}
                </div>
            </div>);
    }
}


var test = {
    index: 0,
    idArticle: "196",
    quantity: "7",
    unitName: "pièce",
    articleName: "Une boite de six oeufs"
}


const OrderItem = (props)=>{
    return (
        <div className="d-flex justify-content-between">
            <div>
                <span>{props.quantity} {props.unitName} : </span>
                <strong>{props.articleName}</strong>
            </div>
            <a href={vars.baseUrl + "article/edit/" + props.idArticle}>Voir</a>
        </div>);
}
