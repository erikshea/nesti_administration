class IngredientList extends React.Component {
    state = { ingredientRecipes: [], ingredients: [], units: [] };
    addFields = { 'status': 'toAdd' };

    constructor(props) {
        super(props);
        this.remove = this.remove.bind(this);
        this.add = this.add.bind(this);
    }

    componentDidMount() {
        $.post(
            vars.baseUrl + 'ajax/getIngredientRecipes',
            {
                idRecipe : vars.entity.idRecipe,
                csrf_token : vars.csrf_token
            },
            (response) => { this.setState(response); }
        );
    }
    
    remove(index) {
        let newIngredientRecipes = [...this.state.ingredientRecipes];
        newIngredientRecipes[index].status = "toDelete";

        this.updateSource(newIngredientRecipes);
    }

    add(e) {
        e.preventDefault();
        let newIngredientRecipes = [...this.state.ingredientRecipes];

        newIngredientRecipes.push(this.addFields);

        this.updateSource(newIngredientRecipes);
    }
    
    updateSource(newIrs) {
        $.post(
            vars.baseUrl + 'ajax/updateIngredientRecipes',
            {
                idRecipe : vars.entity.idRecipe,
                csrf_token : vars.csrf_token,
                ingredientRecipes: newIrs
            },
            (response) => { this.setState(response); }
        );
    }


    render() {
        const ingredientRecipes = this.state.ingredientRecipes.map((ir, index) => {
            return <IngredientRecipe key={index} remove={()=>this.remove(index)} {...ir}/>;
        });

        const ingredients = this.state.ingredients.map((ingredient, index) => {
            return (<option key={index} value={ingredient.name} />);
        });

        const units = this.state.units.map((unit, index) => {
            return (<option key={index} value={unit.name} />);
        });

        return (
            <div className="ingredient-list d-flex flex-column w-100">
                <div className="ingredient-list__items primary-border flex-column">
                    <div className={this.state.ingredientRecipes ? "invisible" : ""}>Aucun ingrédient.</div>
                    {ingredientRecipes}
                </div>
                <form className="ingredient-list__add d-flex flex-column">
                    <h3>Ajouter un ingrédient</h3>
                    <input required
                        onChange={(e)=>{this.addFields.ingredientName = e.target.value}}
                        className="w-100 mr-3 mb-3 ingredient-list__ingredient-name"
                        list="ingredient-suggestions"
                        placeholder="Ingrédient"/>
                    <datalist id="ingredient-suggestions">
                        {ingredients}
                    </datalist>
                    <div className="d-flex " >
                        <input required
                            onChange={(e)=>{this.addFields.quantity = e.target.value}}
                            className="w-50 mr-3 ingredient-list__quantity"
                            placeholder="Quantité"/>
                        <input required
                            onChange={(e)=>{this.addFields.unitName = e.target.value}}
                            className="w-25 mr-3 ingredient-list__unit"
                            list="unit-suggestions"
                            placeholder="Unité"/>
                        <datalist id="unit-suggestions">
                            {units}
                        </datalist>
                        <button type="submit"
                            onClick={this.add}
                            className="w-25 ingredient-list__add-button btn btn-sm btn-success">
                            OK
                        </button>
                    </div>
                </form>
                <div id="ingredient-list__delete-modal"></div>
            </div>
        );
    }
}

const IngredientRecipe = (props)=>{
    return (
        <div className='ingredient-list__ingredient d-flex justify-content-between'>
            <div className="ingredient-list__description">
                <span>{props.quantity} {props.unitName} : </span>
                <strong>{props.ingredientName}</strong>
            </div>
            <a href="#recipe-ingredients"
                onClick={ ()=>ReactDOM.render(
                    <DeleteModal
                        elementName={props.ingredientName}
                        confirm={() => props.remove(props.index)}/>,
                    document.getElementById("ingredient-list__delete-modal")) }
                className="ingredient-list__delete">
                Supprimer
            </a>
        </div>
    );
}

let ingredientsDiv = document.getElementById('recipe__ingredients');
if ( ingredientsDiv ){
    $(() => ReactDOM.render(<IngredientList />, ingredientsDiv) );
}
