/**
 * Utilities for the admin dashboard
 */

jQuery( document ).ready( function( $ ) {
	'use strict';

	// Select Icon on Click
	$( 'body' ).on( 'click', '.js-selectable-icon', function ( ev ) {
		ev.preventDefault();
		var $this = $( this );
		$this.siblings( '.js-icon-input' ).val( $this.data( 'iconname' ) ).change();
	} );

	// Show/hide CTA box on change (Instagram widget).
	$( document ).on( 'change', '.js-cta-box-control', function() {
		if( $( this ).is( ':checked' ) ) {
			$( this ).parent().siblings( '.js-cta-box' ).show();
		}
		else {
			$( this ).parent().siblings( '.js-cta-box' ).hide();
		}
	});

	// Show/hide CTA url inputs on url_type change (Special Offer widget).
	$( document ).on( 'change', '.js-pt-feature_cta_type', function() {
		$( this ).parent().siblings( '.js-pt-feature-custom-url, .js-pt-feature-wc-product' ).toggle();
	});

} );


/********************************************************
 			Backbone code for repeating fields in widgets
********************************************************/

// Namespace for Backbone elements
window.Adrenaline = {
	Models:    {},
	ListViews: {},
	Views:     {},
	Utils:     {},
};

/**
 ******************** Backbone Models *******************
 */

_.extend( Adrenaline.Models, {
	Timetable: Backbone.Model.extend( {
		defaults: {
			'link':        '',
			'month':        '',
			'day':        '',
			'name':        '',
			'description': '',
			'price':       '',
		}
	} )
} );

/**
 ******************** Backbone Views *******************
 */

// Generic single view that others can extend from
Adrenaline.Views.Abstract = Backbone.View.extend( {
	initialize: function ( params ) {
		this.templateHTML = params.templateHTML;

		return this;
	},

	render: function () {
		this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );

		return this;
	},

	destroy: function ( ev ) {
		ev.preventDefault();

		this.remove();
		this.model.trigger( 'destroy' );
	},
} );

_.extend( Adrenaline.Views, {

	// View of a single timetable row.
	Timetable: Adrenaline.Views.Abstract.extend( {
		className: 'pt-widget-single-timetable',

		events: {
			'click .js-pt-remove-timetable-item': 'destroy',
		},
	} )
} );



/**
 ******************** Backbone ListViews *******************
 *
 * Parent container for multiple view nodes.
 */

Adrenaline.ListViews.Abstract = Backbone.View.extend( {

	initialize: function ( params ) {
		this.widgetId     = params.widgetId;
		this.itemsModel   = params.itemsModel;
		this.itemView     = params.itemView;
		this.itemTemplate = params.itemTemplate;

		// Cached reference to the element in the DOM
		this.$items = this.$( params.itemsClass );

		// Collection of items(locations, people, testimonials,...),
		this.items = new Backbone.Collection( [], {
			model: this.itemsModel
		} );

		// Listen to adding of the new items
		this.listenTo( this.items, 'add', this.appendOne );

		return this;
	},

	addNew: function ( ev ) {
		ev.preventDefault();

		var currentMaxId = this.getMaxId();

		this.items.add( new this.itemsModel( {
			id: (currentMaxId + 1)
		} ) );

		return this;
	},

	getMaxId: function () {
		if ( this.items.isEmpty() ) {
			return -1;
		}
		else {
			var itemWithMaxId = this.items.max( function ( item ) {
				return parseInt( item.id, 10 );
			} );

			return parseInt( itemWithMaxId.id, 10 );
		}
	},

	appendOne: function ( item ) {
		var renderedItem = new this.itemView( {
			model:        item,
			templateHTML: jQuery( this.itemTemplate + this.widgetId ).html()
		} ).render();

		var currentWidgetId = this.widgetId;

		// If the widget is in the initialize state (hidden), then do not append a new item
		if ( '__i__' !== currentWidgetId.slice( -5 ) ) {
			this.$items.append( renderedItem.el );
		}

		return this;
	}
} );

_.extend( Adrenaline.ListViews, {

	// Collection of all timetable items, but associated with each individual widget
	Timetables: Adrenaline.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-timetable-item': 'addNew'
		}
	} )
} );

/**
 ******************** Repopulate Functions *******************
 */

_.extend( Adrenaline.Utils, {
	// Generic repopulation function used in all repopulate functions
	repopulateGeneric: function ( collectionType, parameters, json, widgetId ) {
		var collection = new collectionType( parameters );

		// Convert to array if needed
		if ( _( json ).isObject() ) {
			json = _( json ).values();
		}

		// Add all items to collection of newly created view
		collection.items.add( json, { parse: true } );
	},

	/**
	 * Function which adds the existing timetable items to the DOM
	 * @param  {json} timetableJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateTimetable: function ( timetableJSON, widgetId ) {
		var parameters = {
			el:           '#timetable-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.timetable-items',
			itemTemplate: '#js-pt-timetable-',
			itemsModel:   Adrenaline.Models.Timetable,
			itemView:     Adrenaline.Views.Timetable,
		};

		this.repopulateGeneric( Adrenaline.ListViews.Timetables, parameters, timetableJSON, widgetId );
	}
} );
