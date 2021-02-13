$ ( () => {
    $("#recipe-paragraphs__add-button").click(() => {
        $.post( baseUrl + 'recipe/addParagraphAjax', {"blag":"ddd"}, (response) =>{ 
            alert(response);
        });
    });
})

var test = "sdsdsqdsqdsq";

class ParagraphList extends React.Component {
    state = {paragraphs:[]};

    constructor(props) {
        super(props);

        $.post( baseUrl + 'recipe/getParagraphsAjax', {}, (response) =>{ 
            this.state.paragraphs = JSON.parse(response);
        });

        this.remove = this.remove.bind(this);
    }

    add() {
        let newParagraphs = {...this.state.paragraphs};

        newParagraphs.push({ 'content' : '', 'id' : null });

        this.setState({paragraphs:newParagraphs});
    }

    move(index, ammount) {
        let newParagraphs = {...this.state.products};
        newParagraphs.move(index, index+ammount);

        this.setState({paragraphs:newParagraphs});
    }

    remove(index) {
        let newParagraphs = {...this.state.products};
        delete newParagraphs[index];

        this.setState({paragraphs:newParagraphs});
    }

    setState(state) {
        $.post( baseUrl + 'recipe/updateParagraphsAjax', {"paragraphs":state.paragraphs}, (response) =>{ 
            state.paragraphs = response.paragraphs;
        });
        
        super.setState(state);
    }

    render() {
        const paragraphs = Object.keys(this.state.paragraphs).map( (paragraph,index) => 
            <Paragraph
                key={index}
                isFirst = {index == 0}
                isLast = {index == this.state.paragraphs.length - 1}
                remove={this.remove}
                move={this.move}
                {...this.state.paragraphs[index]}
            />
        );

        return (
        <div>
            <div className="w-100 list-group">
                <div className={products.length?"invisible":""}>Aucun paragraphe.</div>
                {paragraphs}
            </div>
            <a id="recipe-paragraphs__add-button" href="#"><i class="far fa-plus-square"></i></a>
        </div>
        );
    }
}

const Paragraph = (props) => {
    let isFirst = props.position == 1;
    let isLast = props.position == props.paragra;


    return (<div className="list-group-item list-group-item-action">
        <span className='name'>{props.name}</span>
        <div>
            { !isFirst && <button className='remove' onClick={() => props.move(-1, props.key)}></button> }
            <button className='remove' onClick={() => props.remove(props.key)}></button>
            { !isLast && <button className='remove' onClick={() => props.move(1, props.key)}></button> }
        </div>
        
        <div>
            <span className='content'>{props.content}</span>
        </div>
    </div>);
}