class BlockUtils {
    static getLabel(key) {
        switch(key) {
            case 'audio':
                return 'Audio';
            case 'download':
                return 'Download';
            case 'image':
                return 'Image';
            case 'markdown':
                return 'Markdown';
            case 'video':
                return 'Video';
        }

        return key;
    }
}

export default BlockUtils;
