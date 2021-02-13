

class ParagraphList extends React.Component {
    state = { paragraphs: [] };
    inputValues = [];

    constructor(props) {
        super(props);
        this.remove = this.remove.bind(this);
        this.add = this.add.bind(this);
        this.move = this.move.bind(this);
        this.updateInputValue = this.updateInputValue.bind(this);
        this.updateSource = this.updateSource.bind(this);
    }

    componentDidMount() {
        $.post(baseUrl + 'recipe/getParagraphsAjax/' + urlParameters[2], {}, (response) => {
            let responseParagraphs = JSON.parse(response);
            this.setState({ paragraphs: responseParagraphs });
            this.inputValues = responseParagraphs.map( (paragraph) => { return paragraph.content } );
        });
    }

    add() {
        let newParagraphs = [...this.state.paragraphs];

        newParagraphs.push({ 'content': '', 'idParagraph': null });

        this.updateSource(newParagraphs);
    }

    move(index, ammount) {
        let newParagraphs = [...this.state.paragraphs];
        newParagraphs.forEach((value, key, me) => {
            me[key].content = this.inputValues[key];
        })
        newParagraphs.move(index, index+ammount);

        this.updateSource(newParagraphs);
    }
    
    remove(index) {
        let newParagraphs = [...this.state.paragraphs];
        newParagraphs[index].toDelete = true;

        this.updateSource(newParagraphs);
    }

    updateInputValue(index,value) {
        this.inputValues[index] = value;
    }

    updateSource(newParagraphs) {
        $.post(baseUrl + 'recipe/updateParagraphsAjax/' + urlParameters[2], { "paragraphs": newParagraphs }, (response) => {
            let responseParagraphs = JSON.parse(response);
            this.setState({ paragraphs: responseParagraphs });
            this.inputValues = responseParagraphs.map( (paragraph) => { return paragraph.content } );
        });
    }


    render() {
        const paragraphs = this.state.paragraphs.map((paragraph, index) => {
            return (<Paragraph
                key={paragraph.idParagraph + paragraph.content}
                index={index}
                isFirst={index == 0}
                isLast={index == this.state.paragraphs.length - 1}
                remove={this.remove}
                move={this.move}
                updateInputValue={this.updateInputValue}
                {...paragraph}
            />);
        }

        );

        return (
            <div className="d-flex align-items-center flex-column paragraph-list">
                <div className="w-100">
                    <div className={paragraphs.length ? "invisible" : ""}>Aucun paragraphe.</div>
                    {paragraphs}
                </div>
                <a id="paragraph-list__add-button" onClick={() => this.add()}><i className="far fa-plus-square"></i></a>
            </div>
        );
    }
}

class Paragraph extends React.Component {
    constructor(props) {
        super(props);
        this.state = { content: props.content }
        this.inputChange = this.inputChange.bind(this);
    }

    inputChange(e){
        this.setState({ content:e.target.value });
        this.props.updateInputValue(this.props.index,e.target.value);
    }

    render() {
        return (
            <div className="d-flex paragraph-list__paragraph align-items-center">
                <div className="paragraph-list__buttons d-flex flex-column">
                    {!this.props.isFirst && <a className='move' onClick={() => this.props.move(this.props.index, -1)}><i className="fas fa-arrow-alt-circle-up"></i></a>}
                    <a className='remove' onClick={() => this.props.remove(this.props.index)}><i className="far fa-trash-alt"></i></a>
                    {!this.props.isLast && <a className='move' onClick={() => this.props.move(this.props.index, 1)}><i className="fas fa-arrow-alt-circle-down"></i></a>}
                </div>

                <div className="move paragraph-list__content">
                    <textarea className='content primary-border'
                        onChange={this.inputChange}
                        ref ={this.props.inputRef}
                        value={`${this.state.content  || ""}`}
                        onBlur ={ ()=> this.props.move(this.props.index, 0)}
                    ></textarea>
                </div>
            </div>);
    }
}

$(() => ReactDOM.render(<ParagraphList />,
    document.getElementById('recipe__paragraph-list')));