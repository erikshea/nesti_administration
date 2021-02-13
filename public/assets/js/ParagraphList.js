

class ParagraphList extends React.Component {
    state = { paragraphs: [] };
    inputRefs = [];

    constructor(props) {
        super(props);
        this.remove = this.remove.bind(this);
        this.add = this.add.bind(this);
        this.move = this.move.bind(this);
        this.updateSource = this.updateSource.bind(this);
    }

    componentDidMount() {
        $.post(baseUrl + 'recipe/getParagraphsAjax/' + urlParameters[2], {}, (response) => {
            this.setState({ paragraphs: JSON.parse(response) });
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
            me[key].content = this.inputRefs[key].current.value;
        })
        newParagraphs.move(index, index+ammount);
        this.inputRefs.move(index, index+ammount);

        this.updateSource(newParagraphs);
    }
    
    remove(index) {
        let newParagraphs = [...this.state.paragraphs];
        delete newParagraphs[index];

        this.updateSource(newParagraphs);
    }

    updateSource(newParagraphs) {
        this.setState({ paragraphs: newParagraphs });
        $.post(baseUrl + 'recipe/updateParagraphsAjax/' + urlParameters[2], { "paragraphs": newParagraphs }, (response) => {
            this.setState({paragraphs:JSON.parse(response)});
        });
    }


    render() {
        const paragraphs = Object.keys(this.state.paragraphs).map((paragraph, index) => {
            if( this.inputRefs[index] === undefined ) {
                this.inputRefs[index] = React.createRef();
            }
            return (<Paragraph
                key={index + this.state.paragraphs[index].content}
                index={index}
                isFirst={index == 0}
                isLast={index == this.state.paragraphs.length - 1}
                remove={this.remove}
                move={this.move}
                handleChange={this.handleChange}
                inputRef={this.inputRefs[index]}
                {...this.state.paragraphs[index]}
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
        this.state = { content: props.content, idParagraph: props.idParagraph }
        this.inputChange = this.inputChange.bind(this);
    }

    inputChange(e){
        this.setState({ content:e.target.value });
    }

    render() {
        return (
            <div className="d-flex paragraph-list__paragraph align-items-center">
                <div className="paragraph-list__buttons d-flex flex-column">
                    {!this.props.isFirst && <a className='move' onClick={() => this.props.move(this.props.index, -1)}><i className="fas fa-arrow-alt-circle-up"></i></a>}
                    <a className='remove' onClick={() => this.props.remove(props.index)}><i className="far fa-trash-alt"></i></a>
                    {!this.props.isLast && <a className='move' onClick={() => this.props.move(this.props.index, 1)}><i className="fas fa-arrow-alt-circle-down"></i></a>}
                </div>

                <div className="move paragraph-list__content">
                    <div>{this.props.idParagraph}</div>
                    <textarea className='content primary-border'
                        onChange={this.inputChange}
                        ref ={this.props.inputRef}
                        value={`${this.state.content  || ""}`}
                    ></textarea>
                </div>
            </div>);
    }
}

$(() => ReactDOM.render(<ParagraphList />,
    document.getElementById('recipe__paragraph-list')));