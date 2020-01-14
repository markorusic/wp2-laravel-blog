import toastr from 'toastr'
import moment from 'moment'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import postActions from './index'

const onCommentSubmit = asyncEventHandler(event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $content = $(event.currentTarget).find('[name="content"]')
    const content = $content.val()
    return http
        .post(`/posts/${postActions.id}/comment`, { content })
        .then(({ data }) => {
            const user = auth.getUser()
            const $commentList = $('#comment-list')
            const commentHTML = `
            <div class="d-flex" data-comment-id="${data.id}">
                <div class="d-flex mb-2">
                    <img
                        class="avatar mr-3"
                        src="${user.avatar}"
                        alt="${user.name}"
                    >
                </div>
                <div class="d-flex flex-column mb-4 w-100">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <span>${user.name}</span>
                            <a href="#" class="text-dark" data-user-action="remove-comment">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </div>
                        <span class="text-secondary">
                            ${moment(data.created_at).format('MMM D, Y')}
                        </span>
                    </div>
                    <div>${content}</div>
                </div>
            </div>
        `
            $content.val('')
            $commentList.prepend(commentHTML)
            $commentList
                .children()
                .first()
                .find('[data-user-action="remove-comment"]')
                .on('click', onCommentRemove)
        })
})

const onCommentRemove = asyncEventHandler(event => {
    const $comment = $(event.currentTarget).closest('[data-comment-id]')
    if (confirm('Are you sure that you want to delete this comment?')) {
        const { commentId } = $comment.data()
        return http
            .delete(`/posts/${postActions.id}/comment/${commentId}/remove`)
            .then(() => {
                toastr.success('Successfully deleted!')
                $comment.remove()
            })
    }
})

const comment = {
    init() {
        $('#comment-form').on('submit', onCommentSubmit)
        $('#comment-list [data-user-action="remove-comment"]').on(
            'click',
            onCommentRemove
        )
    }
}

export default comment