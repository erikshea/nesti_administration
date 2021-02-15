
class ParagraphList extends React.Component {
    state = { paragraphs: [] };
     // we need to keep input values separated from state to avoid re-rendering all paragraphs when just one is edited.
     // state and input values will be synchronized whenever a paragraph is moved, added or deleted.
    inputValues = [];

    constructor(props) {
        super(props);
        this.remove = this.remove.bind(this);
        this.add = this.add.bind(this);
        this.move = this.move.bind(this);
        this.updateInputValue = this.updateInputValue.bind(this);
        this.updateSource = this.synchronizeSource.bind(this);
    }

    componentDidMount() {
        $.post(vars['baseUrl'] + 'recipe/getParagraphsAjax/' + vars['entity']['idRecipe'], {}, (response) => {
            let responseParagraphs = JSON.parse(response);
            this.setState({ paragraphs: responseParagraphs });
            // initialize input values from source content
            this.inputValues = responseParagraphs.map( (paragraph) => { return paragraph.content } );
        });
    }

    add() {
        let newParagraphs = [...this.state.paragraphs];
        
        newParagraphs.push({ 'content': '', 'status': 'toAdd' }); // status tells source to create a new paragraph

        this.synchronizeSource(newParagraphs);
    }

    move(index, ammount) {
        let newParagraphs = [...this.state.paragraphs];

        // need to update content state with current input values before sending to source
        newParagraphs.forEach((value, key, me) => {
            me[key].content = this.inputValues[key]; 
        })
        newParagraphs.move(index, index+ammount);

        this.synchronizeSource(newParagraphs);
    }
    
    remove(index) {
        if (window.confirm('Voulez-vous vraiment effacer ce paragraphe?')){
            let newParagraphs = [...this.state.paragraphs];
            
            newParagraphs[index].status = "toDelete"; // tells source to delete paragraph
    
            this.synchronizeSource(newParagraphs);
        }
    }

    updateInputValue(index,value) {
        this.inputValues[index] = value;
    }

    synchronizeSource(newParagraphs) {
        // send new paragraph data to source
        $.post(vars['baseUrl'] + 'recipe/updateParagraphsAjax/' + vars['entity']['idRecipe'], { "paragraphs": newParagraphs }, (response) => {
            let responseParagraphs = JSON.parse(response); // receive updated paragraph data.
            this.setState({ paragraphs: responseParagraphs }); // component state is now synchronized with source
            this.inputValues = responseParagraphs.map( (paragraph) => { return paragraph.content } ); // as are sanitized input values
        });
    }


    render() {
        const paragraphs = this.state.paragraphs.map((paragraph, index) => {
            return <Paragraph
                key={paragraph.idParagraph + paragraph.content} // key includes content, to re-mount if content changes on add,remove,move...
                index={index}
                isLast={index == this.state.paragraphs.length - 1}
                remove={this.remove}
                move={this.move}
                updateInputValue={this.updateInputValue} // keep track of input values
                {...paragraph}
            />;
        }

        );

        return (
            <div className="d-flex align-items-center flex-column paragraph-list">
                <div className="w-100">
                    <div className={paragraphs.length ? "invisible" : ""}>Aucun paragraphe.</div>
                    {paragraphs}
                </div>
                <a id="paragraph-list__add-button" onClick={this.add}><i className="far fa-plus-square"></i></a>
            </div>
        );
    }
}

const Paragraph = (props) => { 
    let isFirst = (props.index == 0);
    
    return (
        <div className="d-flex paragraph-list__paragraph align-items-center">
            <div className="paragraph-list__buttons d-flex flex-column">
                {!isFirst && <a
                    className='move'
                    onClick={() => props.move(props.index, -1)}>
                        <i className="fas fa-arrow-alt-circle-up"></i>
                </a>}
                <a
                    className='remove'
                    onClick={() => props.remove(props.index)}>
                        <i className="far fa-trash-alt"></i>
                </a>
                {!props.isLast && <a
                    className='move'
                    onClick={() => props.move(props.index, 1)}>
                        <i className="fas fa-arrow-alt-circle-down"></i>
                </a>}
            </div>

            <div className="move paragraph-list__content">
                <textarea className='content primary-border'
                    onChange={ (e)=>{props.updateInputValue(props.index,e.target.value)}}
                    defaultValue={props.content  || ""}
                    onBlur ={ ()=> props.move(props.index, 0)} // on unfocus, move 0 distance to save content to source
                ></textarea>
            </div>
        </div>);
}

Array.prototype.move = function(from, to) {
    this.splice(to, 0, this.splice(from, 1)[0]);
};

let paragraphsDiv =  document.getElementById('recipe__paragraph-list');
if ( paragraphsDiv ){
    $(() => ReactDOM.render(<ParagraphList />, paragraphsDiv));
}
