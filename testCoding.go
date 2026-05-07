package main

import (
	"fmt"
)

/*
1. Buat fungsi untuk mencari 3 angka dari array, yang ketika dijumlahkan menghasilkan
nilai 0, jika tidak ada, return “Not Found”, jika ada ,return angka sesuai urutan
penjumlahan
Example
Input :
$array_number = [2,1,5,7,4,-8,-3,-1]
Output : -3,1,2
(-3 + 1 + 2 = 0)
*/
func zeroThreeSum(arr []int) interface{} {
	result := []int{}
	for i := 0; i < len(arr)-2; i++ {
		for j := i + 1; j < len(arr)-1; j++ {
			for k := j + 1; k < len(arr); k++ {
				if arr[i]+arr[j]+arr[k] == 0 {
					result = append(result, arr[i], arr[j], arr[k])
					return result
				}
			}
		}
	}
	return "Not Found"
}

/*
2. Buat fungsi untuk menghilangkan angka yang sama dari array , tanpa menggunakan fungsi `array_unique`
Example
Input :
$array_number = [1,1,4,4,4,5,5,6,8,9,10,10,12,13,13,17]
Output : 1,4,5,6,8,9,10,12,13,17
*/
func uniqueArray(arr []int) []int {
	uniqueMap := make(map[int]bool)
	uniqueList := []int{}

	for _, num := range arr {
		if _, exists := uniqueMap[num]; !exists {
			uniqueMap[num] = true
			uniqueList = append(uniqueList, num)
		}
	}
	return uniqueList
}

/*
3. Buat fungsi untuk sorting angka dari array tanpa menggunakan fungsi php `array_sort`, sorting secara ascending, namun setiap kelipatan 5 angka dari array tersorting, metode sorting berubah dari ascending ke descending, dan berubah lagi sebaliknya setiap keliapatan 5 angka
Example
Input :
$array_number = [2,5,1,12,-5,4,-1,3,-3,20,8,7,-2,6,9]
Output : -5,-3,-2,-1,1,20,12,9,8,7,2,3,4,5,6
*/
func sortEveryFive(arr []int) []int {
	// copy array agar tidak merubah array asli
	sorted := make([]int, len(arr))
	copy(sorted, arr)

	// fase 1: sorting array secara ascending dengan bubble sort
	for i := 0; i < len(sorted)-1; i++ {
		for j := 0; j < len(sorted)-i-1; j++ {
			if sorted[j] > sorted[j+1] {
				sorted[j], sorted[j+1] = sorted[j+1], sorted[j]
			}
		}
	}

	// fase 2: membuat array baru untuk menyimpan hasil sorting dengan metode yang berubah setiap 5 angka
	result := make([]int, 0, len(sorted))
	// inisialisasi pointer untuk left dan right
	left, right := 0, len(sorted)-1
	chunk := 0

	for left <= right {
		if chunk%2 == 0 {
			// guard untuk memastikan tidak melebihi batas array
			for i := 0; i < 5 && left <= right; i++ {
				result = append(result, sorted[left])
				left++
			}
		} else {
			// guard untuk memastikan tidak melebihi batas array
			for i := 0; i < 5 && left <= right; i++ {
				result = append(result, sorted[right])
				right--
			}
		}
		chunk++
	}
	return result

}

/*
4. Buat fungsi untuk mengecek string memiliki kata yang simetris tanpa menggunakan fungsi php `strrev“, contohnya madam, tutut, jika kata yang dikirim simetris, return TRUE, jika tidakreturn FALSE
Example 1
Input :
$str = madam
Output : TRUE
Example 2
Input :
$str = gozaru
Output : FALSE
*/
func isSymmetric(s string) bool {
	left, right := 0, len(s)-1
	for left < right {
		if s[left] != s[right] {
			return false
		}
		left++
		right--
	}
	return true
}

func main() {
	// Test case for zeroThreeSum
	arrayNumber1 := []int{2, 1, 5, 7, 4, -8, -3, -1}
	result := zeroThreeSum(arrayNumber1)
	fmt.Printf("T1. Q: %v A: %v\n", arrayNumber1, result)

	// Test case for uniqueArray
	arrayNumber2 := []int{1, 1, 4, 4, 4, 5, 5, 6, 8, 9, 10, 10, 12, 13, 13, 17}
	uniqueResult := uniqueArray(arrayNumber2)
	fmt.Printf("T2. Q: %v A: %v\n", arrayNumber2, uniqueResult)

	// Test case for sortEveryFive
	arrayNumber3 := []int{2, 5, 1, 12, -5, 4, -1, 3, -3, 20, 8, 7, -2, 6, 9}
	sortedResult := sortEveryFive(arrayNumber3)
	fmt.Printf("T3. Q: %v A: %v\n", arrayNumber3, sortedResult)

	// Test case for isSymmetric
	fmt.Printf("T4. Q: %s A: %v\n", "madam", isSymmetric("madam"))
	fmt.Printf("T4. Q: %s A: %v\n", "gozaru", isSymmetric("gozaru"))
}
