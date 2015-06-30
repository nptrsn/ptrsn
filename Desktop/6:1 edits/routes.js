// Configuration
Router.configure( {
  layoutTemplate : 'MainLayout'
} );

// Login
Router.route( '/', function() {
  this.render( 'SignUp' );
} );

// Label Maker – Category
Router.route( '/labelmaker/category', function() {
  this.render( 'LabelMaker_Category' );
} );

// Label Maker – Template
Router.route( '/labelmaker/template', function() {
  this.render( 'LabelMaker_Template' );
} );

// Label Maker – Editor
Router.route( '/labelmaker/editor', function() {
  this.render( 'LabelMaker_Editor' );
} );

// Label Maker – Upload
Router.route( '/labelmaker/upload', function() {
  this.render( 'LabelMaker_Upload' );
} );

// On Site - Mixer
Router.route( '/onsite/mixer', function() {
  this.render( 'OnSite_Mixer' );
} );

// On Site - Submit
Router.route( '/onsite/submit', function() {
  this.render( 'OnSite_Submit' );
} );

// Remote Mixer
Router.route( '/remotemixer', function() {
  this.render( 'RemoteMixer' );
} );

// Remote Mixer – Raymond
Router.route( '/remotemixer/raymond', function() {
  this.render( 'RemoteMixer_Raymond' );
} );

// Remote Mixer – Deloach
Router.route( '/remotemixer/deloach', function() {
  this.render( 'RemoteMixer_Deloach' );
} );

// Remote Mixer – Buena Vista
Router.route( '/remotemixer/buenavista', function() {
  this.render( 'RemoteMixer_BuenaVista' );
} );

// Remote Mixer – Submit
Router.route( '/remotemixer/submit', function() {
  this.render( 'RemoteMixer_Submit' );
} );

// Confirmation Page
Router.route( '/confirmation', function() {
  this.render( 'Confirmation' );
} );

// Legal Page
Router.route( '/legal', function() {
  this.render( 'Legal' );
} );

// Shipping Page
Router.route( '/shipping', function() {
  this.render( 'Shipping' );
} );

// Video Page
Router.route( '/video', function() {
  this.render( 'Video' );
} );
