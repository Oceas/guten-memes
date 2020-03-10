const { __ } = wp.i18n; // Import __() from wp.i18n
const { RichText, MediaUpload, PlainText } = wp.editor;
const { registerBlockType } = wp.blocks;
const { Button } = wp.components;

// Import our CSS files
import './editor.scss';
import './style.scss';

import axios from "axios";

registerBlockType('gc-card-block/dad-joke', {
	title: __('Dad Joke'), // Block title.
	icon: 'slides',
	category: 'common',
	attributes: {
		joke: {
			source: 'text',
			selector: '.card-joke'
		},
	},

	edit: props => {

		const {
			attributes: { joke },
			className,
			setAttributes
		} = props;

		const loadJoke = () => {
			axios.get("https://icanhazdadjoke.com/", {
				headers: {'Accept': 'application/json'}
			}).then( response => {
				setAttributes({ joke: response.data.joke })
			});
		};

		return (
			<div className={className}>
				<p>{joke}</p>
				<button onClick={ () => { loadJoke() } }>New Joke</button>
			</div>
		);
	},

	save({ attributes }) {
		return (
			<div className="card">
				<div className="card-content">
					<p className="card-joke">{attributes.joke}</p>
				</div>
			</div>
		);
	}
});