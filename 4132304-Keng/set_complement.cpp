#include <iostream>
#include <iomanip>
#include <string>

using namespace std;

const int U_MIN = 0;
const int U_MAX = 100;

struct Node {
    int data;
    Node* link;
};

// ฟังก์ชันสำหรับรับข้อมูล set จากผู้ใช้
int countNodes(Node* head) {
    int count = 0;
    while (head != nullptr) {
        count++;
        head = head->link;
    }
    return count;
}

Node* search(Node* head, int value) {
    while (head != nullptr) {
        if (head->data == value) {
            return head;
        }
        head = head->link;
    }
    return nullptr;
}

bool insertNode(Node** head, int value) {
    if (search(*head, value) != nullptr) {
        return false;
    }

    Node* newNode = new Node;
    newNode->data = value;
    newNode->link = nullptr;

    if (*head == nullptr || (*head)->data > value) {
        newNode->link = *head;
        *head = newNode;
        return true;
    }

    Node* curr = *head;
    while (curr->link != nullptr && curr->link->data < value) {
        curr = curr->link;
    }
    newNode->link = curr->link;
    curr->link = newNode;
    return true;
}

bool contains(Node* head, int value) {
    while (head != nullptr && head->data <= value) {
        if (head->data == value) {
            return true;
        }
        head = head->link;
    }
    return false;
}

void destroySet(Node** head) {
    Node* curr = *head;
    while (curr != nullptr) {
        Node* next = curr->link;
        delete curr;
        curr = next;
    }
    *head = nullptr;
}

Node* readSet(const string& name) {
    Node* result = nullptr;
    int n;
    
    cout << "\n=== Enter Set " << name << " ===" << endl;
    cout << "How many elements in set " << name << "? ";
    cin >> n;
    
    if (n <= 0) {
        cout << "Set " << name << " is empty." << endl;
        return result;
    }
    
    cout << "Enter " << n << " numbers (0-100), separated by spaces: ";
    for (int i = 0; i < n; i++) {
        int x;
        cin >> x;
        if (x >= U_MIN && x <= U_MAX) {
            insertNode(&result, x);
        } else {
            cout << "  (Warning: " << x << " is out of range, ignored)" << endl;
        }
    }
    
    cout << "Set " << name << " received successfully!" << endl;
    return result;
}

// ฟังก์ชันสำหรับแสดง set
void printSet(Node* head, const string& name) {
    cout << name << " = { ";
    bool first = true;
    while (head != nullptr) {
        if (!first) cout << ", ";
        cout << head->data;
        first = false;
        head = head->link;
    }
    cout << " }" << endl;
}

// ฟังก์ชันสำหรับคำนวณ complement
Node* computeComplement(Node* s) {
    Node* complement = nullptr;
    for (int i = U_MIN; i <= U_MAX; i++) {
        if (!contains(s, i)) {
            insertNode(&complement, i);
        }
    }
    return complement;
}

// ฟังก์ชันสำหรับแสดง complement พร้อมรายละเอียด
void printComplement(Node* s, const string& name, Node* complement) {
    cout << "\n+-------------------------------------------------------+" << endl;
    cout << "| Computing Complement: C = " << name << "'                        " << endl;
    cout << "+-------------------------------------------------------+" << endl;
    
    // 1. แสดง set เดิม
    cout << "\n1. Original Set " << name << ":" << endl;
    cout << "   ";
    printSet(s, name);
    cout << "   => Size of " << name << " = " << countNodes(s) << " elements" << endl;
    
    // 2. แสดง Universal Set
    cout << "\n2. Universal Set U:" << endl;
    cout << "   U = { 0, 1, 2, 3, ..., 100 }" << endl;
    cout << "   => Size of U = 101 elements" << endl;
    
    // 3. คำนวณและแสดง complement
    cout << "\n3. Complement " << name << "' = U - " << name << ":" << endl;
    cout << "   C = { ";
    
    int count = 0;
    bool first = true;
    Node* curr = complement;
    while (curr != nullptr && count < 20) {
        if (!first) cout << ", ";
        cout << curr->data;
        first = false;
        count++;
        curr = curr->link;
    }
    
    int compSize = countNodes(complement);
    if (compSize > 20) {
        cout << ", ... (and " << (compSize - 20) << " more elements)";
    }
    cout << " }" << endl;
    
    cout << "   => Size of C = " << compSize << " elements" << endl;
    
    // 4. สรุปผล
    cout << "\n4. Result Summary:" << endl;
    cout << "   +------------------+----------+" << endl;
    cout << "   | Set              | Size     |" << endl;
    cout << "   +------------------+----------+" << endl;
    cout << "   | " << name << "                | " << setw(3) << countNodes(s) << "      |" << endl;
    cout << "   | " << name << "'               | " << setw(3) << compSize << "      |" << endl;
    cout << "   | U                | 101      |" << endl;
    cout << "   +------------------+----------+" << endl;
}

int main() {
    Node* setA = nullptr;
    Node* setB = nullptr;
    
    cout << "======================================================" << endl;
    cout << "           MINI PROJECT: Set Operations" << endl;
    cout << "======================================================" << endl;
    cout << "  Topic 3: Set Complement (A' and B')" << endl;
    cout << "  Universal Set: U = { x | 0 <= x <= 100 }" << endl;
    cout << "======================================================" << endl;
    
    // รับข้อมูล set A และ B
    setA = readSet("A");
    setB = readSet("B");
    
    // ข้อ 1: แสดง set ของจำนวนสมาชิก
    cout << "\n======================================================" << endl;
    cout << "              REQUIREMENT 1: Display Sets" << endl;
    cout << "======================================================" << endl;
    cout << "\nSet A: ";
    printSet(setA, "A");
    cout << "Set B: ";
    printSet(setB, "B");
    
    // ข้อ 2: หาจำนวนสมาชิกของแต่ละ set
    cout << "\n======================================================" << endl;
    cout << "         REQUIREMENT 2: Set Sizes" << endl;
    cout << "======================================================" << endl;
    cout << "\nSize of Set A = " << countNodes(setA) << " elements" << endl;
    cout << "Size of Set B = " << countNodes(setB) << " elements" << endl;
    
    // ข้อ 3: คำนวณ complement ของ A และ B
    cout << "\n======================================================" << endl;
    cout << "    REQUIREMENT 3: Complement Calculation" << endl;
    cout << "======================================================" << endl;
    
    Node* compA = computeComplement(setA);
    Node* compB = computeComplement(setB);

    printComplement(setA, "A", compA);
    cout << endl;
    printComplement(setB, "B", compB);
    
    // สรุปผลรวม
    cout << "\n======================================================" << endl;
    cout << "              FINAL SUMMARY TABLE" << endl;
    cout << "======================================================" << endl;
    cout << "\n+----------------------+----------+----------+" << endl;
    cout << "| Description          | Set A    | Set B    |" << endl;
    cout << "+----------------------+----------+----------+" << endl;
    cout << "| Original Set Size    | " << setw(3) << countNodes(setA) 
         << "      | " << setw(3) << countNodes(setB) << "      |" << endl;
    cout << "| Complement Size      | " << setw(3) << countNodes(compA) 
         << "      | " << setw(3) << countNodes(compB) << "      |" << endl;
    cout << "| Universal Set Size   | 101      | 101      |" << endl;
    cout << "+----------------------+----------+----------+" << endl;
    cout << "| Verification:        |          |          |" << endl;
    
    bool verifyA = (countNodes(setA) + countNodes(compA) == 101);
    bool verifyB = (countNodes(setB) + countNodes(compB) == 101);
    
    cout << "| |A| + |A'| = |U|     | " << setw(3) << countNodes(setA) << "+" 
         << setw(3) << countNodes(compA) << "=101 (" 
         << (verifyA ? "OK" : "  ") << ") |" << endl;
    cout << "| |B| + |B'| = |U|     | " << setw(3) << countNodes(setB) << "+" 
         << setw(3) << countNodes(compB) << "=101 (" 
         << (verifyB ? "OK" : "  ") << ") |" << endl;
    cout << "+----------------------+----------+----------+" << endl;
    
    // ข้อ 4: มีการแจ้งเตือนว่าได้ส่งค่าไปแล้ว (ทำแล้วในฟังก์ชัน readSet)
    cout << "\n======================================================" << endl;
    cout << "              Program Completed Successfully!" << endl;
    cout << "======================================================" << endl;
    
    destroySet(&setA);
    destroySet(&setB);
    destroySet(&compA);
    destroySet(&compB);
    return 0;
}
