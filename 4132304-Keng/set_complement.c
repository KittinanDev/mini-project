#include <stdio.h>
#include <stdbool.h>

enum { U_MIN = 0, U_MAX = 100 };

static void read_set(bool set_flags[U_MAX + 1], const char *name) {
    int n = 0;
    printf("\n=== Enter Set %s ===\n", name);
    printf("How many elements in set %s? ", name);
    if (scanf("%d", &n) != 1 || n < 0) {
        printf("Invalid input. Using empty set.\n");
        return;
    }

    if (n == 0) {
        printf("Set %s is empty.\n", name);
        return;
    }

    printf("Enter %d numbers (0-100), separated by spaces: ", n);
    for (int i = 0; i < n; i++) {
        int x = 0;
        if (scanf("%d", &x) == 1) {
            if (x >= U_MIN && x <= U_MAX) {
                set_flags[x] = true;
            } else {
                printf("  (Warning: %d is out of range, ignored)\n", x);
            }
        }
    }
    printf("Set %s received successfully!\n", name);
}

static int get_set_size(const bool set_flags[U_MAX + 1]) {
    int count = 0;
    for (int i = U_MIN; i <= U_MAX; i++) {
        if (set_flags[i]) {
            count++;
        }
    }
    return count;
}

static void print_set(const bool set_flags[U_MAX + 1], const char *name) {
    printf("%s = { ", name);
    bool first = true;
    for (int i = U_MIN; i <= U_MAX; i++) {
        if (set_flags[i]) {
            if (!first) {
                printf(", ");
            }
            printf("%d", i);
            first = false;
        }
    }
    printf(" }\n");
}

static void compute_complement(const bool set_flags[U_MAX + 1], bool result[U_MAX + 1]) {
    for (int i = U_MIN; i <= U_MAX; i++) {
        result[i] = !set_flags[i];
    }
}

static void print_complement(const bool set_flags[U_MAX + 1], const char *name) {
    printf("\n+-------------------------------------------------------+\n");
    printf("| Computing Complement: C = %s'                        \n", name);
    printf("+-------------------------------------------------------+\n");
    
    printf("\n1. Original Set %s:\n", name);
    printf("   ");
    print_set(set_flags, name);
    int original_size = get_set_size(set_flags);
    printf("   => Size of %s = %d elements\n", name, original_size);

    printf("\n2. Universal Set U:\n");
    printf("   U = { 0, 1, 2, 3, ..., 100 }\n");
    printf("   => Size of U = 101 elements\n");

    printf("\n3. Complement %s' = U - %s:\n", name, name);
    printf("   C = { ");

    bool first = true;
    int count = 0;
    int total = 0;
    for (int i = U_MIN; i <= U_MAX; i++) {
        if (!set_flags[i]) {
            total++;
            if (count < 20) {
                if (!first) {
                    printf(", ");
                }
                printf("%d", i);
                first = false;
                count++;
            }
        }
    }
    if (total > 20) {
        printf(", ... (and %d more elements)", total - 20);
    }
    printf(" }\n");

    printf("   => Size of C = %d elements\n", total);
    
    printf("\n4. Result Summary:\n");
    printf("   +------------------+----------+\n");
    printf("   | Set              | Size     |\n");
    printf("   +------------------+----------+\n");
    printf("   | %s                | %3d      |\n", name, original_size);
    printf("   | %s'               | %3d      |\n", name, total);
    printf("   | U                | 101      |\n");
    printf("   +------------------+----------+\n");
}

int main(void) {
    bool set_a[U_MAX + 1] = { false };
    bool set_b[U_MAX + 1] = { false };
    bool comp_a[U_MAX + 1] = { false };
    bool comp_b[U_MAX + 1] = { false };

    printf("======================================================\n");
    printf("           MINI PROJECT: Set Operations\n");
    printf("======================================================\n");
    printf("  Topic 3: Set Complement (A' and B')\n");
    printf("  Universal Set: U = { x | 0 <= x <= 100 }\n");
    printf("======================================================\n");

    read_set(set_a, "A");
    read_set(set_b, "B");

    printf("\n======================================================\n");
    printf("              REQUIREMENT 1: Display Sets\n");
    printf("======================================================\n");
    printf("\nSet A: ");
    print_set(set_a, "A");
    printf("Set B: ");
    print_set(set_b, "B");

    printf("\n======================================================\n");
    printf("         REQUIREMENT 2: Set Sizes\n");
    printf("======================================================\n");
    int size_a = get_set_size(set_a);
    int size_b = get_set_size(set_b);
    printf("\nSize of Set A = %d elements\n", size_a);
    printf("Size of Set B = %d elements\n", size_b);

    printf("\n======================================================\n");
    printf("    REQUIREMENT 3: Complement Calculation\n");
    printf("======================================================\n");

    print_complement(set_a, "A");

    printf("\n");
    print_complement(set_b, "B");

    // Calculate complements for final summary
    compute_complement(set_a, comp_a);
    compute_complement(set_b, comp_b);
    int size_comp_a = get_set_size(comp_a);
    int size_comp_b = get_set_size(comp_b);

    printf("\n======================================================\n");
    printf("              FINAL SUMMARY TABLE\n");
    printf("======================================================\n");
    printf("\n+----------------------+----------+----------+\n");
    printf("| Description          | Set A    | Set B    |\n");
    printf("+----------------------+----------+----------+\n");
    printf("| Original Set Size    | %3d      | %3d      |\n", size_a, size_b);
    printf("| Complement Size      | %3d      | %3d      |\n", size_comp_a, size_comp_b);
    printf("| Universal Set Size   | 101      | 101      |\n");
    printf("+----------------------+----------+----------+\n");
    printf("| Verification:        |          |          |\n");
    printf("| |A| + |A'| = |U|     | %3d+%3d=101 (%s) |\n", 
           size_a, size_comp_a, (size_a + size_comp_a == 101) ? "OK" : "    ");
    printf("| |B| + |B'| = |U|     | %3d+%3d=101 (%s) |\n", 
           size_b, size_comp_b, (size_b + size_comp_b == 101) ? "OK" : "    ");
    printf("+----------------------+----------+----------+\n");

    printf("\n======================================================\n");
    printf("              Program Completed Successfully!\n");
    printf("======================================================\n");
    return 0;
}
