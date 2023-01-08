export class G964 {
    public static doubles(maxk: number, maxn: number): number {
        let sum = 0;

        for (let k = 1; k <= maxk; k++) {
            for (let n = 1; n <= maxn; n++) {
                sum += 1 / (k * Math.pow((n + 1), 2 * k));
            }
        }

        return sum;
    }
}