const baseConfig = require( '@wordpress/scripts/config/jest-unit.config.js' );

module.exports = {
	...baseConfig,
	preset: 'ts-jest',
	testEnvironment: 'jsdom',
	setupFilesAfterEnv: [ './jest.setup.js' ],
	transform: {
		'^.+\\.jsx?$': 'babel-jest',
	},
	moduleNameMapper: {
		uuid: require.resolve( 'uuid' ),
	},
};
