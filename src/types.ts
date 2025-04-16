export interface selectProps {
	getCurrentPostId: () => number;
	getEditedPostContent: () => any;
	getEditedPostAttribute: ( attribute: string ) => any;
}
