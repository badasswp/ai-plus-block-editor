export interface selectProps {
	getCurrentPostId: () => number;
	getEditedPostContent: () => any;
	getEditedPostAttribute: ( attribute: string ) => any;
}

export interface selectBlockProps {
	getSelectedBlock: () => any;
	getSelectedBlockClientId: () => string;
	getBlockAttributes: ( clientId: string ) => any;
	getBlock: ( clientId: string ) => any;
}

export interface noticeProps {
	getNotices: () => any;
}
