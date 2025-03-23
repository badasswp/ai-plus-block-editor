const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,

	entry: {
		app: './src/index.tsx',
	},

	output: {
		path: path.resolve( __dirname, 'dist' ),
		filename: '[name].js',
	},

	resolve: {
		extensions: [ '.tsx', '.ts', '.js' ],
	},

	module: {
		rules: [
			{
				test: /\.ts(x)?$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'ts-loader',
					options: {
						configFile: 'tsconfig.json',
						transpileOnly: true,
					},
				},
			},
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [
							'@babel/preset-env',
							'@babel/preset-react',
							'@babel/preset-typescript',
						],
					},
				},
			},
			{
				test: /\.(png|jpg|jpeg|gif|svg)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: 'images/',
						},
					},
				],
			},
			{
				test: /\.scss$/,
				use: [ 'style-loader', 'css-loader', 'sass-loader' ],
			},
		],
	},

	devtool: 'source-map',
	mode: 'production',
};
