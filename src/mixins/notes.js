var notesMixin = {


    methods: {


        processNoteData: function (noteData) {

            var noteTags = [];

            for (var index in noteData.tags) {

                let tagData = noteData.tags[index];
                let tagUrl  = this.tagUrl(tagData.name);

                let tag = {
                    url:        tagUrl,
                    name:       tagData.name,
                    title:      tagData.title,
                    active:     this.$route.params.tag == tagData.name
                };

                noteTags.push(tag);
            }

            let noteUrl     = this.noteUrl(noteData);
            let noteDate    = new Date(noteData.date);
                                
            return {
                url:            noteUrl,
                date:           noteDate,
                tags:           noteTags,                
                name:           noteData.name,                
                title:          noteData.title,
                content:        noteData.content,
                description:    noteData.description
            };

        }, // processNoteData


        noteUrl: function (note) {

            var url = '';

            if (note.name !== undefined) {

                let tag = this.$route.params.tag;
                
                url = tag != undefined ? `/notes/tags/${tag}/${note.name}` : `/notes/${note.name}`;

            }

            return url;

        }, // noteUrl


        tagTitle: function (tagTitle) {

            if (! tagTitle) return tagTitle;

            return tagTitle[0].toUpperCase() + tagTitle.slice(1);

        }, // tagTitle


        tagUrl: function (tagName) {

            return `/notes/tags/${tagName}`;

        }, // tagUrl        


    } // methods


}; // notesMixin

export default notesMixin;