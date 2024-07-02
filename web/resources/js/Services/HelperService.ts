export class HelperService {
    static isKeyInEnum(key: string, enumeration: { [s: string]: string }): boolean {
        return Object.values(enumeration).includes(key);
    }

    static formatDate(date): string | null {
        if (!date) return null;
        const d = new Date(date);
        const month = `${d.getMonth() + 1}`.padStart(2, '0');
        const day = `${d.getDate()}`.padStart(2, '0');
        const year = d.getFullYear();
        return `${year}-${month}-${day}`;
    };

    static truncateText(text: string | null): string {
        if (!text) return '';

        if (text.length <= 100) {
            return text;
        } else {
            return text.substring(0, 100) + '...';
        }
    }

    static formatNumberWithCommas(number: number): string {
        return number.toLocaleString('en-US');
    }

    static formatNumber(number: number): string {
        if (number >= 1000 && number < 1000000) {
            return (number / 1000).toFixed(1) + "K";
        } else if (number >= 1000000) {
            return (number / 1000000).toFixed(1) + "M";
        } else {
            return number.toString();
        }
    }
}
