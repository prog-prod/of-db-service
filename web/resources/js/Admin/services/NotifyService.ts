import Swal from "sweetalert2/dist/sweetalert2.js";

export type NotificationType = 'success' | 'error' | 'message';

export class NotifyService{

    static showError(text: string) {
        this.show("error", text);
    }
    static showSuccess(text: string) {
        this.show("success", text);
    }

    static showMessage(text: string) {
        this.show("message", text);
    }
    static show(type: NotificationType , text: string) {
        let icon: string = type;
        if(type === 'message'){
            icon = 'info';
        }
        Swal.fire({
            icon,
            text,
        });
    }
}
